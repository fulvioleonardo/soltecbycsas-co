<template>
  <main class="content">
    <div class="container-fluid">
      <v-app id="document_form" class="mb-4">
        <div class="content-header">
          <h1>Nuevo Comprobante</h1>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <v-btn :href="`/client/${resource}`" color="warning" class="no-decoration">Volver</v-btn>
              </li>
            </ol>
          </nav>
        </div>
        <v-card v-if="apply_save_invoice">
          <v-list three-line subheader>
            <v-form data-vv-scope="document">
              <v-container>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md6 lg4>
                    <v-autocomplete
                      v-model="document.client_id"
                      v-validate="'required'"
                      data-vv-name="client_id"
                      :counter="255"
                      :items="clients"
                      item-text="name"
                      item-value="id"
                      label="Cliente *"
                      required
                      :disabled="editMode"
                    ></v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg4>
                    <v-menu
                      ref="menu_date_issue"
                      v-model="menuDateIssue"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="document.date_issue"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <template v-slot:activator="{on}">
                        <v-text-field
                          v-model="document.date_issue"
                          label="Fecha de emisión *"
                          prepend-icon="event"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="document.date_issue"
                        locale="es-co"
                        :max="toDate"
                        no-title
                        scrollable
                      >
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="menuDateIssue = false">Cancelar</v-btn>
                        <v-btn
                          flat
                          color="primary"
                          @click="$refs.menu_date_issue.save(document.date_issue)"
                        >OK</v-btn>
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                  <template v-if="editMode">
                    <v-flex xs12 sm12 md6 lg4>
                      <v-autocomplete
                        v-model="document.type_document_id"
                        v-validate="'required'"
                        data-vv-name="type_document_id"
                        :counter="255"
                        :items="typeNoteDocuments"
                        item-text="name"
                        item-value="id"
                        label="Tipo de nota *"
                        @change="conceptss"
                        required
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm12 md6 lg4>
                      <v-autocomplete
                        v-model="document.note_concept_id"
                        v-validate="'required'"
                        data-vv-name="note_concept_id"
                        :counter="255"
                        :items="noteConcepts"
                        item-text="name"
                        item-value="id"
                        label="Concepto *"
                        required
                      ></v-autocomplete>
                    </v-flex>
                  </template>
                  <template v-else>
                    <v-flex xs12 sm12 md6 lg4>
                      <v-autocomplete
                        v-model="document.type_invoice_id"
                        v-validate="'required'"
                        data-vv-name="type_invoice_id"
                        :counter="255"
                        :items="typeInvoices"
                        item-text="name"
                        item-value="id"
                        label="Tipo de factura *"
                        required
                      ></v-autocomplete>
                    </v-flex>
                  </template>
                  <v-flex xs12 sm12 md6 lg4>
                    <v-autocomplete
                      v-model="document.currency_id"
                      v-validate="'required'"
                      data-vv-name="currency_id"
                      :counter="255"
                      :items="currencies"
                      item-text="name"
                      item-value="id"
                      label="Moneda *"
                      required
                      :disabled="editMode"
                    ></v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg4>
                    <v-menu
                      ref="menu_date_issue"
                      v-model="menuDateExpiration"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="document.date_expiration"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <template v-slot:activator="{on}">
                        <v-text-field
                          v-model="document.date_expiration"
                          clearable
                          label="Fecha de vencimiento *"
                          prepend-icon="event"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="document.date_expiration"
                        locale="es-co"
                        :min="toDate"
                        no-title
                        scrollable
                      >
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="menuDateExpiration = false">Cancelar</v-btn>
                        <v-btn
                          flat
                          color="primary"
                          @click="$refs.menu_date_issue.save(document.date_expiration)"
                        >OK</v-btn>
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg4>
                    <v-autocomplete
                      v-model="document.payment_form_id"
                      v-validate="'required'"
                      data-vv-name="payment_form_id"
                      :counter="255"
                      :items="payment_forms"
                      item-text="name"
                      item-value="id"
                      label="Forma de Pago"
                      required
                    ></v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm12 md6 lg4>
                    <v-autocomplete
                      v-model="document.payment_method_id"
                      v-validate="'required'"
                      data-vv-name="payment_method_id"
                      :counter="255"
                      :items="payment_methods"
                      item-text="name"
                      item-value="id"
                      label="Medio de Pago"
                      required
                    ></v-autocomplete>
                  </v-flex>
                  <v-flex v-show="document.payment_form_id == 2" xs12 sm12 md6 lg4>
                    <v-text-field v-model="document.time_days_credit" label="Plazo Credito"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md12 lg12 class="text-lg-right">
                    <v-textarea
                      v-model="document.observation"
                      v-validate="'max:255'"
                      data-vv-name="observation"
                      :counter="255"
                      auto-grow
                      label="Observación"
                      rows="2"
                    ></v-textarea>
                  </v-flex>
                  <v-flex xs12 sm12 md12 lg12 class="text-lg-right">
                    <v-btn flat @click="dialogItems = !dialogItems" class="grey lighten-2">
                      <v-icon>add</v-icon>Agregar productos
                    </v-btn>
                    <v-btn flat @click="dialogRetention = !dialogRetention" class="grey lighten-2">
                      <v-icon>add</v-icon>Agregar retención
                    </v-btn>
                  </v-flex>
                  <v-flex xs12 sm12 md12 lg12 xl12>
                    <v-data-table :headers="columnItems" :items="document.items" hide-actions>
                      <template slot="items" slot-scope="props">
                        <td>{{props.item.name}}</td>
                        <td>{{props.item.type_unit.name}}</td>
                        <td>
                          <v-text-field
                            v-model="props.item.quantity"
                            v-validate="'decimal:2|max:6'"
                            data-vv-name="quantity"
                            required
                          ></v-text-field>
                        </td>
<!--                        <td>{{props.item.quantity | numberFormat}}</td> -->
                        <td>
                          <v-text-field
                            v-model="props.item.price"
                            v-validate="'decimal:2|max:13'"
                            :prefix="currencySymbol"
                            data-vv-name="price"
                            required
                          ></v-text-field>
                        </td>
<!--                        <td>{{ratePrefix()}}{{props.item.price | numberFormat}}</td>  -->
                        <td>
                          <v-autocomplete
                            v-model="props.item.tax_id"
                            :suffix="`${ratePrefix()}${props.item.total_tax}`"
                            :counter="255"
                            :items="itemTaxes"
                            item-text="name"
                            item-value="id"
                          ></v-autocomplete>
                        </td>
                        <td>{{ratePrefix()}}{{props.item.subtotal | numberFormat}}</td>
                        <td>
                          <v-text-field
                            v-model="props.item.discount"
                            v-validate="'decimal:2|max:13'"
                            :prefix="currencySymbol"
                            data-vv-name="discount"
                            :counter="13"
                            required
                          ></v-text-field>
                        </td>
                        <td>{{ratePrefix()}}{{props.item.total | numberFormat}}</td>
                        <td class="text-lg-right text-md-right text-sm-right text-xs-right">
                          <v-icon small @click="deleteItem(props.item)">delete</v-icon>
                        </td>
                      </template>
                    </v-data-table>
                  </v-flex>
                  <v-flex v-if="showTotalAndSave" xs12 sm12 md12 lg12 xl12>
                    <v-layout justify-end xs12 sm12 md12 lg12 xl12>
                      <table>
                        <tr>
                          <td class="text-right">
                            <div class="text-uppercase">Total venta:</div>
                          </td>
                          <td class="text-right">{{ratePrefix()}}{{document.sale | numberFormat}}</td>
                        </tr>
                        <tr>
                          <td class="text-right">
                            <div class="text-uppercase">Total descuento(-):</div>
                          </td>
                          <td
                            class="text-right"
                          >{{ratePrefix()}}{{document.total_discount | numberFormat}}</td>
                        </tr>
                        <template v-for="(tax, index) in document.taxes">
                          <tr v-if="((tax.total > 0) && (!tax.is_retention))">
                            <td class="text-right">
                              <div class="text-uppercase">{{tax.name}}(+):</div>
                            </td>
                            <td
                              class="text-right"
                            >{{ratePrefix()}}{{Number(tax.total).toFixed(2) | numberFormat}}</td>
                          </tr>
                        </template>
                        <tr>
                          <td class="text-right">
                            <div class="text-uppercase">Subtotal:</div>
                          </td>
                          <td
                            class="text-right"
                          >{{ratePrefix()}}{{document.subtotal | numberFormat}}</td>
                        </tr>
                        <template v-for="(tax, index) in taxes">
                          <tr v-if="((tax.is_retention) && (tax.apply))">
                            <td class="text-right">{{tax.name}}(-)</td>
                            <td class="text-right">
                              <v-text-field
                                :prefix="currencySymbol"
                                :value="tax.retention"
                                append-icon="close"
                                @click:append="deleteRetention(tax)"
                                readonly
                              ></v-text-field>
                            </td>
                          </tr>
                        </template>
                        <tr>
                          <td class="text-right">
                            <div class="text-uppercase title">Total:</div>
                          </td>
                          <td class="text-right">{{ratePrefix()}}{{document.total | numberFormat}}</td>
                        </tr>
                      </table>
                    </v-layout>
                  </v-flex>
                </v-layout>
              </v-container>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  flat
                  :loading="loading"
                  @click="validate('document')"
                  :disabled="!showTotalAndSave"
                >Guardar</v-btn>
              </v-card-actions>
            </v-form>
          </v-list>
        </v-card>
        <v-dialog v-model="dialogItems" max-width="600px" style="height: 0px; top: -100px;">
          <v-card>
            <v-list three-line subheader>
              <v-form data-vv-scope="add">
                <v-container>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md6 lg6>
                      <v-autocomplete
                        v-model="add.item_id"
                        v-validate="'required'"
                        data-vv-name="item_id"
                        :counter="255"
                        :items="items"
                        item-text="name"
                        item-value="id"
                        label="Producto *"
                        required
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm12 md6 lg6>
                      <v-text-field
                        v-model="add.quantity"
                        v-validate="'required|decimal:2|max:13'"
                        data-vv-name="quantity"
                        :counter="13"
                        label="Cantidad *"
                        required
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-form>
            </v-list>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn flat @click="dialogItems = false">Cerrar</v-btn>
              <v-btn flat @click="validateItem('add')">Agregar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogRetention" max-width="600px" style="height: 0px; top: -100px;">
          <v-card>
            <v-list three-line subheader>
              <v-form data-vv-scope="retention">
                <v-container>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-autocomplete
                        v-model="retention.retention_id"
                        :suffix="`${retentionSelected.rate}%`"
                        v-validate="'required'"
                        :error-messages="errors.collect('retention.retention_id')"
                        data-vv-name="retention_id"
                        :counter="255"
                        :items="retentiontaxes"
                        item-text="name"
                        item-value="id"
                        label="Retención *"
                        required
                      ></v-autocomplete>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-form>
            </v-list>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn flat @click="dialogRetention = false">Cerrar</v-btn>
              <v-btn flat @click="validateRetention('retention')">Agregar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-app>
    </div>
  </main>
</template>

<script>
import Helper from "../../../mixins/Helper";
import { all } from 'q';
// import VeeValidate, { Validator } from 'vee-validate';
window.Vue = require('vue');
window.VeeValidate = require('vee-validate');

window.Vue.use(window.VeeValidate);
export default {
  mixins: [Helper],
  props: {
    route: {
      // required: true
    }
  },
  data: () => ({
    company: null,
    resolution: null,
    menuDateExpiration: false,
    resource: "documents",
    dialogRetention: false,
    dialogItems: false,
    menuDateIssue: false,
    loadDataTable: false,
    noteConcepts: [],
    editMode: false,
    columnItems: [
      {
        text: "Producto",
        value: "name",
        sortable: false
      },
      {
        text: "Unidad",
        value: "type_unit_id",
        sortable: false
      },
      {
        text: "Cantidad",
        value: "quantity",
        sortable: false
      },
      {
        text: "Precio unitario",
        value: "price",
        sortable: false
      },
      {
        text: "Impuesto",
        value: "tax_id",
        sortable: false
      },
      {
        text: "Subtotal",
        value: "subtotal",
        sortable: false
      },
      {
        text: "Descuento",
        value: "discount",
        sortable: false
      },
      {
        text: "Total",
        value: "total",
        sortable: false
      },
      {
        text: "Acciones",
        value: "actions",
        sortable: false
      }
    ],
    columns: [
      {
        text: "#",
        value: "id"
      },
      {
        text: "Fecha de emisión",
        value: "date_issue"
      },
      {
        text: "Cliente",
        value: "client.name"
      },
      {
        text: "Documento",
        value: "number"
      },
      {
        text: "Estado",
        value: "number"
      },
      {
        text: "Moneda",
        value: "currency.code"
      },
      {
        text: "Total venta",
        value: "sale"
      },
      {
        text: "Total descuentos",
        value: "total_discount"
      },
      {
        text: "Total impuestos",
        value: "total_tax"
      },
      {
        text: "Subtotal",
        value: "subtotal"
      },
      {
        text: "Total",
        value: "total"
      },
      {
        text: "Acciones",
        value: "actions",
        sortable: false
      }
    ],
    typeDocuments: [],
    typeInvoices: [],
    currencies: [],
    loading: false,
    documents: [],
    retention: {},
    document: {},
    clients: [],
    search: "",
    items: [],
    taxes: [],
    add: {},
    payment_methods: [],
    payment_forms: [],
    apply_save_invoice: true,
    tax_amount_calculate: [],
    add_retentions: []
  }),
  computed: {
    itemTaxes() {
      return this.taxes.filter(tax => !tax.is_retention);
    },
    retentiontaxes() {
      return this.taxes.filter(tax => tax.is_retention);
    },
    retentionSelected() {
      if (this.retention.retention_id == null) return { rate: 0 };

      return this.taxes.find(row => row.id == this.retention.retention_id);
    },
    showTotalAndSave() {
      return (
        this.document.hasOwnProperty("items") && this.document.items.length > 0
      );
    },
    typeNoteDocuments() {
      return this.typeDocuments.filter(row => row.id != 1);
    }
  },
  watch: {
    document: {
      handler(val) {
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
            if (!tax.hasOwnProperty("total")) tax.total = Number(0).toFixed(2);

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

        this.taxes.forEach(tax => {
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

        val.total = Number(total).toFixed(2);
      },
      deep: true
    },
    typeDocuments: {
      handler(val) {
        val.forEach(row => {
          if (row.alert_range)
            this.$root.$emit("addSnackbarNotification", {
              text: `La resolución de ${row.name} esta próxima a vencer por rango.`,
              color: "warning"
            });
          if (row.alert_date)
            this.$root.$emit("addSnackbarNotification", {
              text: `La resolución de ${row.name} esta próxima a vencer por fecha de vigencia.`,
              color: "warning"
            });
        });
      },
      deep: true
    }
  },
  mounted() {
    this.refreshData();
  },
  methods: {
    cleanForm() {
      this.document = {
        type_document_id: this.typeDocuments.find(
          typeDocument => typeDocument.id == 1
        ).id,
        currency_id: this.company.currency_id,
        date_issue: this.toDate,
        type_invoice_id: 1,
        total_discount: 0,
        total_tax: 0,
        watch: false,
        subtotal: 0,
        items: [],
        total: 0,
        sale: 0,
        time_days_credit: 0
      };

      this.taxes.forEach(tax => {
        tax.apply = false;
        tax.retention = 0;
        tax.total = 0;
      });

      this.menuDateIssue = false;
      this.editMode = false;

      this.errors.clear();
    },
    refreshData() {
      this.loadDataTable = true;
      axios
        .post(`/client/${this.resource}All`)
        .then(response => {
          this.$setLaravelMessage(response.data);

          this.company = response.data.companyservice;
          if (!this.company.resolution_model_api) {
            this.apply_save_invoice = false;
            this.$root.$emit("addSnackbarNotification", {
              text: "No se ha configurado Resolucion",
              color: "warning"
            });
            return false;
          }
          if (!this.company.response_certificate) {
            this.apply_save_invoice = false;
            this.$root.$emit("addSnackbarNotification", {
              text: "No se ha configurado Certificado",
              color: "warning"
            });
            return false;
          }
          if (!this.company.response_software) {
            this.apply_save_invoice = false;
            this.$root.$emit("addSnackbarNotification", {
              text: "No se ha configurado Software",
              color: "warning"
            });
            return false;
          }

          this.resolution = this.company.resolution_model_api;

          this.typeDocuments = response.data.typeDocuments;
          this.typeInvoices = response.data.typeInvoices;
          this.currencies = response.data.currencies;
          this.documents = response.data.documents;
          this.clients = response.data.clients;
          this.items = response.data.items;
          this.taxes = response.data.taxes;
          this.payment_methods = response.data.payment_methods;
          this.payment_forms = response.data.payment_forms;

          this.taxes.forEach(tax => {
            tax.retention = 0;
            tax.total = 0;
          });
        })
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.response.data);
          this.$setLaravelErrors(error.response.data);
        })
        .then(() => {
          this.loadDataTable = false;

          this.cleanForm();
        });
    },
    close() {
      this.dialog = false;

      this.cleanForm();
    },
    cleanItem() {
      this.add = {};
    },
    cleanRetention() {
      this.retention = {};
    },
    validate(scope) {
      this.$validator.validateAll(scope).then(valid => {
        if (valid) {
          this.loading = true;

          this.document.service_invoice = this.createInvoideService();
//          console.log(this.document.service_invoice)
//          return false

          axios
            .post(`/client/${this.resource}`, this.document)
            .then(response => {
              this.$setLaravelMessage(response.data);

              if (response.data.success) {
                this.refreshData();

                this.dialog = false;
              }
            })
            .catch(error => {
              this.$setLaravelValidationErrorsFromResponse(error.response.data);
              this.$setLaravelErrors(error.response.data);
            })
            .then(() => {
              this.loading = false;
            });
        }
      });
    },
    validateItem(scope) {
      this.$validator.validateAll(scope).then(valid => {
        if (valid) {
          let item = JSON.parse(
            JSON.stringify(this.items.find(row => row.id == this.add.item_id))
          );
          let itemExist = this.document.items.find(row => row.id == item.id);

          item.quantity = this.add.quantity;

          if (itemExist != null)
            itemExist.quantity =
              Number(itemExist.quantity) + Number(item.quantity);
          if (itemExist == null && item.quantity != 0)
            this.document.items.push(item);

          this.cleanItem();
        }
      });
    },
    validateRetention(scope) {
      this.$validator.validateAll(scope).then(valid => {
        if (valid) {
          //this
          this.retentionSelected.apply = true;
          this.document.watch = !this.document.watch;

          this.cleanRetention();
        }
      });
    },
    deleteRetention(tax) {
      tax.apply = false;
      tax.retention = 0;

      this.document.watch = !this.document.watch;
    },
    deleteItem(item) {
      this.document.items.splice(
        this.document.items.findIndex(row => row.id == item.id),
        1
      );
    },
    query(document) {
      this.$set(document, "loading", true);

      axios
        .put(`/client/${this.resource}/query/${document.id}`)
        .then(response => {
          this.$setLaravelMessage(response.data);

          if (response.data.success)
            this.documents.splice(
              this.documents.findIndex(row => row.id == document.id),
              1,
              response.data.document
            );
        })
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.response.data);
          this.$setLaravelErrors(error.response.data);
        })
        .then(() => {
          this.$set(document, "loading", false);
        });
    },
    conceptss() {
      this.type_invoice_id = null;
      this.noteConcepts = [];

      if (this.document.type_document_id != null)
        this.getConcepts(this.document.type_document_id).then(
          rows => (this.noteConcepts = rows)
        );
    },

    cadenaDecimales(amount){
      if(amount.toString().indexOf(".") != -1)
        return amount.toString();
      else
        return amount.toString()+".00";
    },

    editDocument(document) {
      let documentClone = JSON.parse(JSON.stringify(document));
      let items = documentClone.detail_documents.map(row => row.item);

      this.taxes.forEach(tax => {
        let taxClone = documentClone.taxes.find(row => row.id == tax.id);

        if (taxClone != null) tax.apply = taxClone.apply;
      });

      items.forEach(item => {
        this.$delete(item, "total_tax");
        this.$delete(item, "subtotal");
        this.$delete(item, "total");
        this.$delete(item, "tax");
      });

      documentClone.taxes.forEach(tax => {
        tax.retention = 0;
        tax.total = 0;
      });

      documentClone = {
        currency_id: documentClone.currency_id,
        client_id: documentClone.client_id,
        reference_id: documentClone.id,
        date_issue: this.toDate,
        type_document_id: null,
        type_invoice_id: null,
        total_discount: 0,
        id: document.id,
        total_tax: 0,
        watch: false,
        subtotal: 0,
        items: items,
        total: 0,
        sale: 0
      };

      this.document = JSON.parse(JSON.stringify(documentClone));
      this.editMode = true;
      this.dialog = true;
    },

    getCustomer() {
      let customer = this.clients.find(x => x.id == this.document.client_id);
      let obj = {
        identification_number: customer.identification_number,
        name: customer.name,
        phone: customer.phone,
        address: customer.address,
        email: customer.email,
        merchant_registration: "000000",
        type_obligation_id: customer.type_obligation_id
      };

      if (customer.type_person_id == 2) {
        obj.dv = customer.dv;
      }

      return obj;
    },

    getTaxTotal() {
      let tax = [];
      this.document.items.forEach(element => {
        let find = tax.find(x => x.tax_id == element.tax.type_tax_id && x.percent == element.tax.rate);
        if(find)
        {
          let indexobj = tax.findIndex(x => x.tax_id == element.tax.type_tax_id && x.percent == element.tax.rate);
          tax.splice(indexobj, 1);
          tax.push({
            tax_id: find.tax_id,
            tax_amount: this.cadenaDecimales(Number(find.tax_amount) + Number(element.total_tax)),
            percent: this.cadenaDecimales(find.percent),
            taxable_amount: this.cadenaDecimales(Number(find.taxable_amount) + Number(element.price) * Number(element.quantity)) - Number(element.discount)
          });
        }
        else {
          tax.push({
            tax_id: element.tax.type_tax_id,
            tax_amount: this.cadenaDecimales(Number(element.total_tax)),
            percent: this.cadenaDecimales(Number(element.tax.rate)),
            taxable_amount: this.cadenaDecimales((Number(element.price) * Number(element.quantity)) - Number(element.discount))
          });
        }
      });
//      console.log(tax);
      this.tax_amount_calculate = tax;
      return tax;
    },

    getLegacyMonetaryTotal() {
      let line_ext_am = 0;
      let tax_incl_am = 0;
      let allowance_total_amount = 0;
      this.document.items.forEach(element => {
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

    getInvoiceLines() {
      let data = this.document.items.map(x => {
        return {
          unit_measure_id: x.type_unit.code, //codigo api dian de unidad
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
          description: x.name,
          code: x.code,
          type_item_identification_id: 4,
          price_amount: this.cadenaDecimales(x.price),
          base_quantity: x.quantity
        };
      });
      return data;
    },

    getWithHolding() {
      let total = this.document.sale
      let list = this.taxes.filter(function(x) {
        return x.is_retention && x.apply;
      });

      return list.map(x => {
        return {
          tax_id: x.type_tax_id,
          tax_amount: this.cadenaDecimales(x.retention),
          percent: this.cadenaDecimales(x.rate / (x.conversion / 100)),
          taxable_amount: this.cadenaDecimales(total),
        };
      });
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

   //  this.createallowancecharge(allowance_total_amount, line_ext_am)

    createInvoideService() {
      let resol = this.resolution.resolution;
      const invoice = {
        number: 0,
        type_document_id: 1
      };
      invoice.customer = this.getCustomer();
      invoice.tax_totals = this.getTaxTotal();
      invoice.legal_monetary_totals = this.getLegacyMonetaryTotal();
      invoice.allowance_charges = this.createAllowanceCharge(invoice.legal_monetary_totals.allowance_total_amount, invoice.legal_monetary_totals.line_extension_amount );

      invoice.invoice_lines = this.getInvoiceLines();
      invoice.with_holding_tax_total = this.getWithHolding();

      return invoice;
    },
    randomNumber(minimum, maximum) {
      return Math.round(Math.random() * (maximum - minimum) + minimum);
    }
  }
};
</script>
