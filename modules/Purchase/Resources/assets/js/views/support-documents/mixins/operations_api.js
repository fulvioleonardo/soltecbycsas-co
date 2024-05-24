// funciones para crear json de documento de soporte para api
export const operations_api = {
    data() {
        return {
            tax_amount_calculate: []
        }
    },
    methods: {
        // funciones para crear json para api
        async createDataApi()
        {
            let form_api = {
                number: null,
                type_document_id: this.form.resolution_code,
                prefix: this.form.prefix,
                resolution_number: this.form.resolution_number,
                date: this.form.date_of_issue,
                time: this.form.time_of_issue,
                notes: this.form.observation,
                sendmail: false,
                seller: {},
                payment_form: {},
                legal_monetary_totals: {},
                allowance_charges: {},
                invoice_lines: {},
                with_holding_tax_total: {},
            }
            form_api.seller =  await this.getSeller()
            form_api.payment_form =  await this.getPaymentForm()
            form_api.legal_monetary_totals = await this.getLegacyMonetaryTotal()
            form_api.allowance_charges = await this.getAllowanceCharge(form_api.legal_monetary_totals.allowance_total_amount, form_api.legal_monetary_totals.line_extension_amount)
            form_api.invoice_lines = await this.getInvoiceLines()
            form_api.with_holding_tax_total = await this.getWithHolding();
            return form_api
        },

        async createDataApiForAdjustNote()
        {
            let form_api = {
                number: null,
                discrepancyresponsecode: this.form.note_concept_id,
                discrepancyresponsedescription: this.form.discrepancy_response_description,
                type_document_id: this.form.resolution_code,
                prefix: this.form.prefix,
                resolution_number: this.form.resolution_number,
                date: this.form.date_of_issue,
                time: this.form.time_of_issue,
                sendmail: false,
                seller: {},
                tax_totals: {},
                legal_monetary_totals: {},
                allowance_charges: {},
                credit_note_lines: {},
            }

            form_api.seller =  await this.getSeller()
            form_api.legal_monetary_totals = await this.getLegacyMonetaryTotal()
            form_api.allowance_charges = await this.getAllowanceCharge(form_api.legal_monetary_totals.allowance_total_amount, form_api.legal_monetary_totals.line_extension_amount)
            form_api.credit_note_lines = await this.getCreditNoteLines()
            form_api.tax_totals = await this.getTaxTotals()

            return form_api
        },
        getTaxTotals() {

            let tax = []
            this.form.items.forEach(element => {
                let find = tax.find(x => x.tax_id == element.tax.type_tax_id && x.percent == element.tax.rate)
                if(find)
                {
                    let indexobj = tax.findIndex(x => x.tax_id == element.tax.type_tax_id && x.percent == element.tax.rate)
                    tax.splice(indexobj, 1)
                    tax.push({
                        tax_id: find.tax_id,
                        tax_amount: this.stringDecimals(Number(find.tax_amount) + Number(element.total_tax)),
                        percent: this.stringDecimals(find.percent),
                        taxable_amount: this.stringDecimals(Number(find.taxable_amount) + Number(element.price) * Number(element.quantity)) - Number(element.discount)
                    })
                }
                else {
                    tax.push({
                        tax_id: element.tax.type_tax_id,
                        tax_amount: this.stringDecimals(Number(element.total_tax)),
                        percent: this.stringDecimals(Number(element.tax.rate)),
                        taxable_amount: this.stringDecimals((Number(element.price) * Number(element.quantity)) - Number(element.discount))
                    })
                }
            })

            this.tax_amount_calculate = tax

            return tax
        },
        getPaymentForm()
        {
            return {
                payment_form_id: this.form.payment_form_id,
                payment_method_id: this.form.payment_method_id,
                payment_due_date: moment(this.form.date_of_issue).add(this.form.time_days_credit, 'days').format('YYYY-MM-DD'),
                duration_measure: this.form.time_days_credit
            }
        },
        getSeller()
        {
            const supplier = _.find(this.suppliers, {id : this.form.supplier_id})

            const seller = {
                identification_number: supplier.number,
                name: supplier.name,
                phone: supplier.telephone,
                address: supplier.address,
                email: supplier.email,
                merchant_registration: '0000000-00',
                type_document_identification_id: supplier.identity_document_type_id,
                type_organization_id: supplier.type_person_id,
                municipality_id_fact: supplier.city_id,
                type_regime_id: supplier.type_regime_id,
                postal_zone_code: supplier.postal_code
            }

            if (supplier.type_person_id == 1)
            {
                seller.dv = supplier.dv
            }

            return seller
        },
        getLegacyMonetaryTotal()
        {
            let line_ext_am = 0
            let tax_incl_am = 0
            let allowance_total_amount = 0

            this.form.items.forEach(element => {
                line_ext_am += (Number(element.price) * Number(element.quantity)) - Number(element.discount)
                allowance_total_amount += Number(element.discount)
            })

            let total_tax_amount = 0
            this.tax_amount_calculate.forEach(element => {
                total_tax_amount += Number(element.tax_amount)
            })

            tax_incl_am = line_ext_am + total_tax_amount

            return {
                line_extension_amount: this.stringDecimals(line_ext_am),
                tax_exclusive_amount: this.stringDecimals(line_ext_am),
                tax_inclusive_amount: this.stringDecimals(tax_incl_am),
                allowance_total_amount: this.stringDecimals(allowance_total_amount),
                charge_total_amount: '0.00',
                payable_amount: this.stringDecimals(tax_incl_am - allowance_total_amount)
            }
        },
        getInvoiceLines()
        {
            return this.form.items.map(x => {
                return {
                    unit_measure_id: x.item.unit_type.code, //codigo api dian de unidad
                    invoiced_quantity: x.quantity,
                    line_extension_amount: this.stringDecimals((Number(x.price) * Number(x.quantity)) - x.discount),
                    free_of_charge_indicator: false,
                    allowance_charges: [
                        {
                            charge_indicator: false,
                            allowance_charge_reason: "DESCUENTO GENERAL",
                            amount: this.stringDecimals(x.discount),
                            base_amount: this.stringDecimals(Number(x.price) * Number(x.quantity))
                        }
                    ],
                    description: x.item.name,
                    code: x.item.internal_id,
                    type_item_identification_id: 4,
                    price_amount: this.stringDecimals(Number(x.price) + (Number(x.total_tax) / Number(x.quantity))),
                    base_quantity: x.quantity,
                    type_generation_transmition_id: x.type_generation_transmition_id,
                    start_date: x.start_date
                }

            })

        },
        getCreditNoteLines()
        {
            return this.form.items.map(x => {
                return {
                    unit_measure_id: x.item.unit_type.code, //codigo api dian de unidad
                    invoiced_quantity: x.quantity,
                    line_extension_amount: this.stringDecimals((Number(x.price) * Number(x.quantity)) - x.discount),
                    free_of_charge_indicator: false,
                    allowance_charges: [
                        {
                            charge_indicator: false,
                            allowance_charge_reason: "DESCUENTO GENERAL",
                            amount: this.stringDecimals(x.discount),
                            base_amount: this.stringDecimals(Number(x.price) * Number(x.quantity))
                        }
                    ],
                    tax_totals: [
                        {
                            tax_id: x.tax.type_tax_id,
                            tax_amount: this.stringDecimals(x.total_tax),
                            taxable_amount: this.stringDecimals((Number(x.price) * Number(x.quantity)) - x.discount),
                            percent: this.stringDecimals(x.tax.rate)
                        }
                    ],
                    description: x.item.name,
                    code: x.item.internal_id,
                    type_item_identification_id: 4,
                    price_amount: this.stringDecimals(Number(x.price) + (Number(x.total_tax) / Number(x.quantity))),
                    base_quantity: x.quantity,
                    brandname: x.item.brand,
                    modelname: x.item.model,
                }

            })

        },
        getWithHolding() {
            let total_iva = this.form.total_tax
            let total = this.form.sale
            let list = this.form.taxes.filter(function(x) {
                return x.is_retention && x.apply;
            });
            return list.map(x => {
                return {
                    tax_id: x.type_tax_id,
                    tax_amount: this.cadenaDecimales(x.retention),
                    percent: this.cadenaDecimales(this.roundNumber(x.rate / (x.conversion / 100), 6)),
                    taxable_amount: x.in_base ? this.cadenaDecimales(total) : this.cadenaDecimales(total_iva),
                };
            });
        },
        roundNumber(num, decimales = 2)
        {
            var signo = (num >= 0 ? 1 : -1)
            num = num * signo
            if (decimales === 0) //con 0 decimales
                return signo * Math.round(num)
            // round(x * 10 ^ decimales)
            num = num.toString().split('e')
            num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)))
            // x * 10 ^ (-decimales)
            num = num.toString().split('e')
            return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales))
        },
        getAllowanceCharge(amount, base)
        {
            return [
                {
                    discount_id: 1,
                    charge_indicator: false,
                    allowance_charge_reason: 'DESCUENTO GENERAL',
                    amount: this.stringDecimals(amount),
                    base_amount: this.stringDecimals(base)
                }
            ]
        },
        stringDecimals(amount)
        {
            if(amount.toString().indexOf(".") != -1)
                return amount.toString()
            else
                return amount.toString()+".00"
        },
        cadenaDecimales(amount){
            if(amount.toString().indexOf(".") != -1)
                return amount.toString();
            else
                return amount.toString()+".00";
            },
        }
    // funciones para crear json para api
}
