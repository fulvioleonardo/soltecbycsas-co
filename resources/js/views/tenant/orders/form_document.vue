<template> </template>

<script>
export default {
    components: { },
    data() {
        return {
            form: {},
            resource: "co-documents",
            taxes: [],
            all_items: [],
            tax_amount_calculate: [],
            all_customers: [],
            type_invoices: [],
            currencies:[],
            payment_methods:[],
            payment_forms:[],
            purchase: {
            },

        };
    },
    async created() {

        await this.initForm();
        await this.initPurchase();
        await this.$http.get(`/${this.resource}/item/tables`).then(response => {
            this.taxes = response.data.taxes;
            this.all_items = response.data.items;
        });

        await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.all_customers = response.data.customers;
                    this.taxes = response.data.taxes
                    // console.log(this.taxes)
                    this.type_invoices = response.data.type_invoices;
                    this.currencies = response.data.currencies
                    this.payment_methods = response.data.payment_methods
                    this.payment_forms = response.data.payment_forms
                    // this.form.currency_id = (this.currencies.length > 0)?this.currencies[0].id:null;
                    // this.form.type_invoice_id = (this.type_invoices.length > 0)?this.type_invoices[0].id:null;
                    this.form.payment_form_id = (this.payment_forms.length > 0)?this.payment_forms[0].id:null;
                    this.form.payment_method_id = (this.payment_methods.length > 0)?this.payment_methods[0].id:null;
        })
    },
    methods: {
        initPurchase()
        {
            this.purchase = {
                items:[],
                customer: null
            }
        },
        initForm() {
            this.form = {
                type_document_id: 1,
                currency_id: 170,
                date_issue: moment().format("YYYY-MM-DD"),
                date_expiration: null,
                type_invoice_id: 1,
                total_discount: 0,
                total_tax: 0,
                watch: false,
                subtotal: 0,
                items: [],
                taxes: [],
                total: 0,
                sale: 0,
                time_days_credit: 0,
                service_invoice: {},
                payment_form_id: null,
                payment_method_id: null,
                customer_id: null
            };
        },
        initFormItem(){
           return {
                    id: null,
                    item_id: null,
                    item: {},
                    code: null,
                    discount: 0,
                    name: null,
                    price: null,
                    quantity: null,
                    subtotal: null,
                    tax: {},
                    tax_id: null,
                    total: 0,
                    total_tax: 0,
                    type_unit: {},
                    unit_type_id: null,
                    item_unit_types: [],
                    IdLoteSelected: null,
                };
        },
        async sendDocument({id, items, customer, status_order_id}) {

            console.log(items)
            await items.forEach(it => {
                it.item_id = it.id
                it.price = it.sale_unit_price
                it.quantity = it.cantidad
            });

            this.purchase.items = items
            this.purchase.customer = customer
            this.form.service_invoice = await this.createInvoiceService();

            this.$emit('handleLoader', true);

            try
            {

                const { data }  = await this.$http.post(`/${this.resource}`, this.form)

                await this.finallyProcess(data.data.id, id, status_order_id)

                this.$emit('handleLoader', false);

                this.$eventHub.$emit('reloadData')


            }catch(error)
            {
                this.$emit('handleLoader', false);
                this.$message.error("Error en validaciòn de datos.");
            }

        },


        finallyProcess(documentId, orderId, status_order_id) {
            this.$http.post(`/ecommerce/transaction_finally2`, {documentId, orderId, status_order_id}).then(response => {
                this.$message.success('Comprobante generado correctamente.')
            }).catch(error => {
                this.$message.error(error.response.data.message)
            }).then(() => {
                this.loading_submit = false
            })
        },
        async createInvoiceService() {
            const invoice = {
                number: 0,
                type_document_id: 1
            };

            invoice.customer = await this.getCustomer();
            invoice.tax_totals = await this.getTaxTotal();
            invoice.legal_monetary_totals = await this.getLegacyMonetaryTotal();
            invoice.allowance_charges = await this.createAllowanceCharge(
                invoice.legal_monetary_totals.allowance_total_amount,
                invoice.legal_monetary_totals.line_extension_amount
            );

            invoice.invoice_lines = await this.getInvoiceLines();
            invoice.with_holding_tax_total = await this.getWithHolding();

            return invoice;
        },
        calculateTotal() {
            this.setDataTotals()
        },
        setDataTotals() {

                // console.log(val)
                let val = this.form
                val.taxes = JSON.parse(JSON.stringify(this.taxes));

                val.items.forEach(item => {
                    item.tax = this.taxes.find(tax => tax.id == item.tax_id);

                    if (
                        item.discount == null ||
                        item.discount == "" ||
                        item.discount > item.price * item.quantity
                    )
                        this.$set(item, "discount", 0);

                    item.total_tax = 0;

                    if (item.tax != null) {
                        let tax = val.taxes.find(tax => tax.id == item.tax.id);

                        if (item.tax.is_fixed_value)

                            item.total_tax = (
                                item.tax.rate * item.quantity -
                                (item.discount < item.price * item.quantity ? item.discount : 0)
                            ).toFixed(2);

                        if (item.tax.is_percentage)

                            item.total_tax = (
                                (item.price * item.quantity -
                                (item.discount < item.price * item.quantity
                                    ? item.discount
                                    : 0)) *
                                (item.tax.rate / item.tax.conversion)
                            ).toFixed(2);

                        if (!tax.hasOwnProperty("total"))
                            tax.total = Number(0).toFixed(2);

                        tax.total = (Number(tax.total) + Number(item.total_tax)).toFixed(2);
                    }

                    item.subtotal = (
                        Number(item.price * item.quantity) + Number(item.total_tax)
                    ).toFixed(2);

                    this.$set(
                        item,
                        "total",
                        (Number(item.subtotal) - Number(item.discount)).toFixed(2)
                    );

                });

                val.subtotal = val.items
                    .reduce(
                        (p, c) => Number(p) + (Number(c.subtotal) - Number(c.discount)),
                        0
                    )
                    .toFixed(2);
                    val.sale = val.items
                    .reduce(
                        (p, c) =>
                        Number(p) + Number(c.price * c.quantity) - Number(c.discount),
                        0
                    )
                    .toFixed(2);
                    val.total_discount = val.items
                    .reduce((p, c) => Number(p) + Number(c.discount), 0)
                    .toFixed(2);
                    val.total_tax = val.items
                    .reduce((p, c) => Number(p) + Number(c.total_tax), 0)
                    .toFixed(2);

                let total = val.items
                    .reduce((p, c) => Number(p) + Number(c.total), 0)
                    .toFixed(2);

                let totalRetentionBase = Number(0);

                // this.taxes.forEach(tax => {
                val.taxes.forEach(tax => {
                    if (tax.is_retention && tax.in_base && tax.apply) {
                        tax.retention = (
                        Number(val.sale) *
                        (tax.rate / tax.conversion)
                        ).toFixed(2);

                        totalRetentionBase =
                        Number(totalRetentionBase) + Number(tax.retention);

                        if (Number(totalRetentionBase) >= Number(val.sale))
                        this.$set(tax, "retention", Number(0).toFixed(2));

                        total -= Number(tax.retention).toFixed(2);
                    }

                    if (
                        tax.is_retention &&
                        !tax.in_base &&
                        tax.in_tax != null &&
                        tax.apply
                    ) {
                        let row = val.taxes.find(row => row.id == tax.in_tax);

                        tax.retention = Number(
                        Number(row.total) * (tax.rate / tax.conversion)
                        ).toFixed(2);

                        if (Number(tax.retention) > Number(row.total))
                        this.$set(tax, "retention", Number(0).toFixed(2));

                        row.retention = Number(tax.retention).toFixed(2);
                        total -= Number(tax.retention).toFixed(2);
                    }
                });

                val.total = Number(total).toFixed(2)

        },
        getFormItem(id, quantity, item)
        {
            const record = this.initFormItem()
            record.item = _.find(this.all_items, {'id': id});
            record.item_unit_types = []
            record.id = id
            record.item_id = id
            record.unit_type_id = item.unit_type_id

            record.tax_id = item.tax_id
            record.tax = _.find(this.taxes, {'id': item.tax_id})

            record.price = record.item.sale_unit_price;
            record.quantity = quantity;
            record.lots_group = record.item.lots_group

            return record
        },
        getCustomer() {
            const { customer } = this.purchase;
            return {
                identification_number: customer.identification_number,
                name: customer.name,
                phone: customer.telephone,
                address: customer.address,
                email: customer.email,
                merchant_registration: "000000",
                identity_document_type_id: 3
            };
        },
        async getTaxTotal() {

            const context = this

            const {items} = this.purchase

            this.form.items = items.map( x=> {
                return context.getFormItem(x.id, x.cantidad, x)
            })

            await context.calculateTotal()

            let tax = [];
            this.form.items.forEach(element => {
                let find = tax.find(
                    x =>
                        x.tax_id == element.tax.type_tax_id &&
                        x.percent == element.tax.rate
                );
                if (find) {
                    let indexobj = tax.findIndex(
                        x =>
                            x.tax_id == element.tax.type_tax_id &&
                            x.percent == element.tax.rate
                    );
                    tax.splice(indexobj, 1);
                    tax.push({
                        tax_id: find.tax_id,
                        tax_amount: this.cadenaDecimales(
                            Number(find.tax_amount) + Number(element.total_tax)
                        ),
                        percent: this.cadenaDecimales(find.percent),
                        taxable_amount:
                            this.cadenaDecimales(
                                Number(find.taxable_amount) +
                                    Number(element.price) *
                                        Number(element.quantity)
                            ) - Number(element.discount)
                    });
                } else {
                    tax.push({
                        tax_id: element.tax.type_tax_id,
                        tax_amount: this.cadenaDecimales(
                            Number(element.total_tax)
                        ),
                        percent: this.cadenaDecimales(Number(element.tax.rate)),
                        taxable_amount: this.cadenaDecimales(
                            Number(element.price) * Number(element.quantity) -
                                Number(element.discount)
                        )
                    });
                }
            });
            this.tax_amount_calculate = tax;
            return tax;
        },
        getLegacyMonetaryTotal() {

            let line_ext_am = 0;
            let tax_incl_am = 0;
            let allowance_total_amount = 0;
            this.form.items.forEach(element => {
                line_ext_am += (Number(element.price) * Number(element.quantity)) - Number(element.discount);
                allowance_total_amount += Number(element.discount);
            });

            let total_tax_amount = 0;
            this.tax_amount_calculate.forEach(element => {
                total_tax_amount += Number(element.tax_amount);
            });

            tax_incl_am = line_ext_am + total_tax_amount;

            return {
                line_extension_amount: this.cadenaDecimales(line_ext_am),
                tax_exclusive_amount: this.cadenaDecimales(line_ext_am),
                tax_inclusive_amount: this.cadenaDecimales(tax_incl_am),
                allowance_total_amount: this.cadenaDecimales(allowance_total_amount),
                charge_total_amount: "0.00",
                payable_amount: this.cadenaDecimales(tax_incl_am - allowance_total_amount)
            };


        },
        createAllowanceCharge(amount, base) {
                return [
                    {
                        discount_id: 1,
                        charge_indicator: false,
                        allowance_charge_reason: "DESCUENTO GENERAL",
                        amount: this.cadenaDecimales(amount),
                        base_amount: this.cadenaDecimales(base)
                    }
                ]
        },
        getInvoiceLines() {

                let data = this.form.items.map(x => {
                    return {

                        unit_measure_id: x.item.unit_type.code, //codigo api dian de unidad
                        invoiced_quantity: x.quantity,
                        line_extension_amount: this.cadenaDecimales((Number(x.price) * Number(x.quantity)) - x.discount),
                        free_of_charge_indicator: false,
                                allowance_charges: [
                            {
                                        charge_indicator: false,
                                        allowance_charge_reason: "DESCUENTO GENERAL",
                                        amount: this.cadenaDecimales(x.discount),
                                        base_amount: this.cadenaDecimales(Number(x.price) * Number(x.quantity))
                                    }
                        ],
                        tax_totals: [
                            {
                                tax_id: x.tax.type_tax_id,
                                tax_amount: this.cadenaDecimales(x.total_tax),
                                taxable_amount: this.cadenaDecimales((Number(x.price) * Number(x.quantity)) - x.discount),
                                percent: this.cadenaDecimales(x.tax.rate)
                            }
                        ],
                        description: x.item.name,
                        code: x.item.internal_id,
                        type_item_identification_id: 4,
                        price_amount: this.cadenaDecimales(x.price),
                        base_quantity: x.quantity
                    };

                });

                return data;
        },

        getWithHolding() {

                let total = this.form.sale
                let list = this.form.taxes.filter(function(x) {
                    return x.is_retention && x.apply;
                });

                return list.map(x => {
                    return {
                        tax_id: x.type_tax_id,
                        tax_amount: this.cadenaDecimales(x.retention),
                        percent: this.cadenaDecimales(x.rate),
                        taxable_amount: this.cadenaDecimales(total),
                    };
                });

        },

        cadenaDecimales(amount){
            if(amount.toString().indexOf(".") != -1)
                return amount.toString();
            else
                return amount.toString()+".00";
        },
    }
};
</script>

<style></style>
