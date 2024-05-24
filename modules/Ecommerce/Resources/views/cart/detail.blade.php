@extends('ecommerce::layouts.layout_ecommerce_cart.index')
@section('content')

<div class="row" id="app">
    <div class="col-lg-8">
        <div class="cart-table-container">

            <table class="table table-cart">
                <thead>
                    <tr>
                        <th class="product-col">Producto</th>
                        <th class="price-col">Precio</th>
                        <th class="qty-col">Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in records" class="product-row">
                        <td class="product-col">
                            <figure class="product-image-container">
                                <a href="#" class="product-image">
                                    <img :src=" '/storage/uploads/items/' + row.image" alt="product" v-if="row.image !=='imagen-no-disponible.jpg'">
                                    <img :src=" '/logo/' + row.image" alt="product" v-else>
                                </a>
                            </figure>
                            <h2 class="product-title">
                                <a href="#">@{{ row.name }}</a>
                            </h2>
                        </td>
                        <td>$ @{{ row.sale_unit_price }}</td>
                        <td>
                            <input class="vertical-quantity form-control input_quantity" :data-product="row.id"
                                type="text">
                        </td>
                        <td>@{{ row.sub_total }}</td>
                        <td>
                            <button type="button" @click="deleteItem(row.id, index)"
                                class="btn btn-outline-danger btn-sm"><i class="icon-cancel"></i></button>
                        </td>
                    </tr>

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4" class="clearfix">
                            <div class="float-left">
                                <a href="/ecommerce" class="btn btn-outline-secondary">Continar Comprando</a>
                            </div><!-- End .float-left -->

                            <div class="float-right">
                                <a href="#" @click="clearShoppingCart"
                                    class="btn btn-outline-secondary btn-clear-cart">Limpiar Carrito</a>
                                <!--<a href="#" class="btn btn-outline-secondary btn-update-cart">Update Shopping Cart</a> -->
                            </div><!-- End .float-right -->
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div><!-- End .cart-table-container -->
    </div><!-- End .col-lg-8 -->

    <div class="col-lg-4">
        <div class="cart-summary">
            <h3>Resumen</h3>
            <table class="table table-totals">
                <tbody>

                    <tr v-if="summary.sale > 0">
                        <td>TOTAL VENTA</td>
                        <td>$ @{{ summary.sale }}</td>
                    </tr>
                    <template v-for="(tax, index) in summary.taxes">
                        <tr v-if="((tax.total > 0) && (!tax.is_retention))" :key="index">
                            <td>@{{tax.name}}(+)</td>
                            <td>$ @{{Number(tax.total).toFixed(2)}}</td>
                        </tr>
                    </template> 
                    {{-- <tr v-if="summary.subtotal > 0">
                        <td>SUBTOTAL</td> 
                        <td>$ @{{ summary.subtotal }}</td>
                    </tr> --}}
                </tbody>
                <tfoot>
                    <tr>
                        <td>Orden Total</td>
                        <td>$ @{{summary.total}}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="checkout-methods text-center">

                @guest
                <a href="#" class="btn btn-block btn-sm btn-primary login-link culqi">
                    Inicia sesion para completar tu compra
                </a>
                @else
                <button @click="payment_cash.clicked = !payment_cash.clicked" class="btn btn-block btn-sm btn-primary">
                    Pagar
                    {{-- Pagar con EFECTIVO  --}}
                </button>
                <div v-show="payment_cash.clicked" style="margin: 3%" class="form-group">
                    <div>
                        <select class="form-control mb-1" v-model="form_document.payment_method_type_id">
                            <option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input readonly placeholder="0.0" v-model="payment_cash.amount" type="text"
                            onkeypress="return isNumberKey(event)" maxlength="14" class="form-control"
                            aria-label="Amount">
                        <button @click="paymentCash" class="btn btn-success">OK!</button>
                    </div>
                </div>
                @endguest
            </div><!-- End .checkout-methods -->
        </div><!-- End .cart-summary -->


        <div class="cart-summary">
            <h3>Datos de contacto y envío</h3>

            <form autocomplete="off" action="#">


                <div class="form-group" :class="{'text-danger': errors.identification_number}">
                    <label for="email">N° Identificación:</label>
                    <input v-model="form_contact.identification_number" onkeypress="return isNumberKey(event)" maxlength="8" type="text" autocomplete="off" class="form-control" placeholder="Ingrese N° Identificación">
                    <small class="form-control-feedback" v-if="errors.identification_number" v-text="errors.identification_number[0]"></small>
                </div>
                <div class="form-group" :class="{'text-danger': errors.telephone}">
                    <label for="email">Teléfono:</label>
                    <input v-model="form_contact.telephone" type="text" maxlength="10" autocomplete="off" class="form-control" placeholder="Ingrese número de teléfono" name="teléfono">
                    <small class="form-control-feedback" v-if="errors.telephone" v-text="errors.telephone[0]"></small>
                </div>
                <div class="form-group" :class="{'text-danger': errors.address}">
                    <label for="email">Dirección:</label>
                    <textarea v-model="form_contact.address" class="form-control" placeholder="Ingrese dirección de envío" rows="2" cols="10"></textarea>
                    <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                </div>
            </form>
        </div>
    </div><!-- End .col-lg-4 -->

</div><!-- End .row -->

<input type="hidden" id="total_amount" data-total="0.0" />

@endsection

@push('scripts')

<script type="text/javascript">
    var app_cart = new Vue({
        el: '#app',
        data: {
            form_contact: {
                address:   null,
                telephone:   null,
                identification_number:   null,
            },
            payment_cash: {
                amount: '',
                clicked: false
            },
            response_search: {},
            text_search: '',
            loading_search: false,
            identity_document_types: [{
                id: '1',
                description: 'DNI'
            }, {
                id: '6',
                description: 'RUC'
            }],
            formIdentity: {
                identity_document_type_id: '6'
            },
            records: [],
            records_old: [],
            order_generated: {},
            summary: {
                subtotal:'0.0',
                sale:'0.0',
                taxes: [],
                tax: '0.0',
                total: '0.0'
            },
            aux_totals: {},
            form_document: {},
            customer: {},

            user: {},
            typeDocumentSelected: '',
            response_order_total:0,
            errors: {},
            taxes: [],
            payment_method_types: []

        },
        computed: {
            maxLength: function () {
                // if (this.formIdentity.identity_document_type_id === '6') {
                //     return 11
                // }
                // if (this.formIdentity.identity_document_type_id === '1') {
                //     return 8
                // }
            }
        },
        async mounted() {

            await this.getTables()
            await this.getTaxes()

            let contex = this
            $(".input_quantity").change(function (e) {
                let value = parseFloat($(this).val())
                let id = $(this).data('product')
                let row = contex.records.find(x => x.id == id)
                row.sub_total = (parseFloat(row.sale_unit_price) * value).toFixed(2)
                row.cantidad = value
                contex.calculateSummary()
            });
            await this.calculateSummary()
        },
        created() {


            this.user = @json(Auth::user());
            let array = localStorage.getItem('products_cart');
            array = JSON.parse(array)
            if (array) {
                this.records = array.map(function (item) {
                    let obj = item
                    obj.cantidad = 1
                    obj.sub_total = parseFloat(item.sale_unit_price).toFixed(2)
                    return obj
                })
            }
            // console.log(this.records)
            this.initForm();

        },
        methods: {
            async getTaxes(){
                await axios.get(`/ecommerce/table/taxes`).then(response => { 
                    this.taxes = response.data
                })
            },
            async getTables(){
                await axios.get(`/ecommerce/tables`).then(response => { 
                    this.payment_method_types = response.data.payment_method_types
                })
            },
            getFormPaymentCash() {

                const customer = this.getCustomer()
                if(!customer)
                    alert("Debe iniciar sesion.")

                //let precio = Math.round(Number(this.summary.total) * 100).toFixed(2);
              //  const tot = Number(this.summary.total)
                return {
                    producto: 'Compras Ecommerce Facturador Pro',
                  //  precio: precio,
                    precio_culqi: Number(this.summary.total),
                    customer: customer,
                    items: this.records,
                    payment_method_type_id: this.form_document.payment_method_type_id,
                    //telephone: this.form_contact.telephone,
                    //address: this.form_contact.address
                }
            },
            async paymentCash() {
              // verifica si tiene productos seleccionado
              let product = JSON.parse(localStorage.getItem('products_cart'));

              if (product.length < 1){
                swal({
                    title: "No se han encontrado productos",
                    text: "Por favor seleccione algún producto de la tienda.",
                    type: "error"
                })
                return
              }

                swal({
                    title: "Estamos generando el Pago.",
                    text: `Por favor no cierre esta ventana hasta que el proceso termine.`,
                    focusConfirm: false,
                    onOpen: () => {
                        Swal.showLoading()
                    }
                });

                let url_finally = '{{ route("tenant_ecommerce_payment_cash")}}';
                
                let response = await axios.post(url_finally, this.getFormPaymentCash(), this.getHeaderConfig()).then(response => {
                if (response.data.success) {
                    this.saveContactDataUser()
                    this.clearShoppingCart()
                    this.response_order_total = response.data.order.total
                    swal({
                        title: "Gracias por su pago!",
                        text: "En breve le enviaremos un correo electronico con los detalles de su compra.",
                        type: "success"
                    }).then((x) => {
                    //   app_cart.order_generated = order
                        //askedDocument(response.data.order);
                    })
                }

              }).catch(error => {

                swal("Pago No realizado", 'Sucedio algo inesperado.', "error");
                if (error.response.status === 422) {
                  this.errors = error.response.data;
                } else {
                  console.log(error);
                }
                
              });

            },
            redirectHome() {
                window.location = "{{ route('tenant.ecommerce.index') }}";
            },
            getHeaderConfig() {
                let token = this.user.api_token
                let axiosConfig = {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`
                    }
                };
                return axiosConfig;
            },
            async getDocument() {
                this.form_document.items = await this.getItemsDocument()
                this.form_document.totales = await this.getTotales()

                return this.form_document
            },
            async getTotales() {

                let totals = await {
                    "total_exportacion": 0.00,
                    "total_operaciones_gravadas": this.aux_totals.total_taxed,
                    "total_operaciones_inafectas": 0.00,
                    "total_operaciones_exoneradas": this.aux_totals.total_exonerated,
                    "total_operaciones_gratuitas": 0.00,
                    "total_igv": this.aux_totals.total_igv,
                    "total_impuestos": this.aux_totals.total_igv,
                    "total_valor": this.aux_totals.total_value,
                    "total_venta": this.aux_totals.total
                }

                return totals
            },
            async getItemsDocument() {

                let rec = await this.records_old.map((item) => {

                    let sale_unit_price = 0
                    let total_exonerated = 0
                    let total_igv = 0
                    let total_val = 0
                    let total = 0
                    let percentage_igv = 18

                    if (item.sale_affectation_igv_type_id === '10') {

                        unit_value = item.sale_unit_price / (1 + percentage_igv / 100)
                        total_igv = item.cantidad * parseFloat(item.sale_unit_price - unit_value)
                        total = (item.cantidad * item.sale_unit_price)
                        sale_unit_price = parseFloat(item.sale_unit_price)
                        total_val = (unit_value * item.cantidad)

                        return {
                            "codigo_interno": (item.internal_id) ? item.internal_id:"",
                            "descripcion": item.description,
                            "codigo_producto_sunat": "",
                            "unidad_de_medida": item.unit_type_id,
                            "cantidad": item.cantidad,
                            "valor_unitario": unit_value,
                            "codigo_tipo_precio": "01",
                            "precio_unitario": sale_unit_price,
                            "codigo_tipo_afectacion_igv": "10",
                            "total_base_igv": total_val,
                            "porcentaje_igv": percentage_igv,
                            "total_igv": total_igv,
                            "total_impuestos": total_igv,
                            "total_valor_item": total_val,
                            "total_item": total
                        }

                    }

                    if (item.sale_affectation_igv_type_id === '20') {

                        unit_value = parseFloat(item.sale_unit_price)
                        total_igv = 0
                        total = (parseFloat(item.cantidad) * parseFloat(item.sale_unit_price))
                        sale_unit_price = parseFloat(item.sale_unit_price)
                        total_val = (parseFloat(unit_value) * parseFloat(item.cantidad))

                        return {
                            "codigo_interno": (item.internal_id) ? item.internal_id:"",
                            "descripcion": item.description,
                            "codigo_producto_sunat": "",
                            "unidad_de_medida": item.unit_type_id,
                            "cantidad": item.cantidad,
                            "valor_unitario": unit_value,
                            "codigo_tipo_precio": "01",
                            "precio_unitario": sale_unit_price,
                            "codigo_tipo_afectacion_igv": "20",
                            "total_base_igv": total_val,
                            "porcentaje_igv": percentage_igv,
                            "total_igv": 0,
                            "total_impuestos": 0,
                            "total_valor_item": total_val,
                            "total_item": total
                        }

                    }

                })

                return rec
            },
            initForm() {

                this.errors = {}

                if(this.user){
                    this.form_contact.address =  this.user.address
                    this.form_contact.telephone =  this.user.telephone
                }

                this.form_document = {
                    type_document_id: 1,
                    currency_id: 170,
                    date_issue: moment().format('YYYY-MM-DD'),
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
                    payment_method_type_id: '01',
                }

                this.formIdentity = {
                    identity_document_type_id: '6'
                }




            },
            deleteItem(id, index) {
                //remove en fronted
                this.records.splice(index, 1)
                //set remove en localstorage
                let array = localStorage.getItem('products_cart');
                array = JSON.parse(array);
                let indexFound = array.findIndex(x => x.id == id)
                array.splice(indexFound, 1);
                localStorage.setItem('products_cart', JSON.stringify(array));

                this.calculateSummary()


            },
            clearShoppingCart() {
                this.errors = {}
                this.records_old = this.records
                this.records = []
                localStorage.setItem('products_cart', JSON.stringify([]))
                // this.calculateSummary()

                this.summary = {
                    subtotal: '0.0',
                    tax: '0.0',
                    total: '0.00',
                    total_taxed: '0.0',
                    total_value: '0.0',
                    total_exonerated: '0.0',
                    total_igv: '0.0'
                }
                this.payment_cash.amount = '0.00'
            },
            calculateSummary() {

                let subtotal = 0.00
                let total_taxed = 0
                let total_value = 0
                let total_exonerated = 0
                let total_igv = 0
                let total = 0

                // this.records.forEach(function (item) {
                //     //console.log(item)
                //     subtotal += parseFloat(item.sub_total)

                //     let unit_price = item.sub_total
                //     let unit_value = unit_price
                //     let percentage_igv = 18

                //     if (item.sale_affectation_igv_type_id === '10') {
                //         unit_value = item.sub_total / (1 + percentage_igv / 100)
                //         total_taxed += parseFloat(unit_value)
                //         total_igv += parseFloat(unit_price - unit_value)
                //     }
                //     if (item.sale_affectation_igv_type_id === '20') {
                //         total_exonerated += parseFloat(unit_value)
                //     }

                //     total_value = total_taxed + total_exonerated
                //     total += parseFloat(unit_price)
                // })

                // console.log(total_taxed, total_exonerated, total_igv)

                // this.summary.total_taxed = total_taxed.toFixed(2)
                // this.summary.total_exonerated = total_exonerated.toFixed(2)
                // this.summary.total_igv = total_igv.toFixed(2)
                // this.summary.total_value = total_value.toFixed(2)
                // this.summary.total = total.toFixed(2)
                // this.aux_totals = this.summary
                // console.log(this.summary)

                this.setDataTotals()


                $("#total_amount").data('total', this.summary.total);

                this.payment_cash.amount = this.summary.total;
 
            },
            setDataTotals() {

                // console.log(val)
                let val = this.summary
                val.taxes = JSON.parse(JSON.stringify(this.taxes));
                // console.log(this.taxes, val, this.records)

                this.records.forEach(item => {
                    item.tax = this.taxes.find(tax => tax.id == item.tax_id);

                    if (
                        item.discount == null ||
                        item.discount == "" ||
                        item.discount > item.sale_unit_price * item.cantidad
                    )
                        this.$set(item, "discount", 0);

                    item.total_tax = 0;

                    if (item.tax != null) {
                        let tax = val.taxes.find(tax => tax.id == item.tax.id);

                        if (item.tax.is_fixed_value)

                            item.total_tax = (
                                item.tax.rate * item.cantidad -
                                (item.discount < item.sale_unit_price * item.cantidad ? item.discount : 0)
                            ).toFixed(2);

                        if (item.tax.is_percentage)

                            item.total_tax = (
                                (item.sale_unit_price * item.cantidad -
                                (item.discount < item.sale_unit_price * item.cantidad
                                    ? item.discount
                                    : 0)) *
                                (item.tax.rate / item.tax.conversion)
                            ).toFixed(2);

                        if (!tax.hasOwnProperty("total"))
                            tax.total = Number(0).toFixed(2);

                        tax.total = (Number(tax.total) + Number(item.total_tax)).toFixed(2);
                    }

                    item.subtotal = (
                        Number(item.sale_unit_price * item.cantidad) + Number(item.total_tax)
                    ).toFixed(2);

                    this.$set(
                        item,
                        "total",
                        (Number(item.subtotal) - Number(item.discount)).toFixed(2)
                    );

                });

                val.subtotal = this.records
                    .reduce(
                        (p, c) => Number(p) + (Number(c.subtotal) - Number(c.discount)),
                        0
                    )
                    .toFixed(2);
                    val.sale = this.records
                    .reduce(
                        (p, c) =>
                        Number(p) + Number(c.sale_unit_price * c.cantidad) - Number(c.discount),
                        0
                    )
                    .toFixed(2);
                    val.total_discount = this.records
                    .reduce((p, c) => Number(p) + Number(c.discount), 0)
                    .toFixed(2);
                    val.total_tax = this.records
                    .reduce((p, c) => Number(p) + Number(c.total_tax), 0)
                    .toFixed(2);

                let total = this.records
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
            saveContactDataUser()
            {
                let url_finally = '{{ route("tenant_ecommerce_user_data")}}';
                axios.post(url_finally, this.form_contact, this.getHeaderConfig())
                    .then(response => {
                       console.log(response.data)
                    })
                    .catch(error => {

                    });
            },
            getCustomer()
            {
                if(!this.user)
                    return null

                return {
                    name: this.user.name,
                    email: this.user.email,
                    type: this.user.type,
                    telephone: this.form_contact.telephone,
                    address: this.form_contact.address,
                    identification_number: this.form_contact.identification_number
                }
            }

        }
    })

</script>

<script>

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

</script>

@endpush
