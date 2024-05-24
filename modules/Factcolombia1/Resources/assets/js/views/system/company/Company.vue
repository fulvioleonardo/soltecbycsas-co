<template>
  <main class="content">
    <div class="container-fluid">
      <v-app id="companies">
        <div class="content-header">
          <h1>Listado de compañías</h1>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <v-btn
                  color="bee darker text-white no-decoration"
                  @click="dialog = !dialog; textModal = 'Nueva compañia'"
                >Nueva compañia</v-btn>
              </li>
            </ol>
          </nav>
        </div>
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
            :headers="columns"
            :items="companies"
            :search="search"
            :loading="loadDataTable"
            class="elevation-1"
          >
            <template slot="items" slot-scope="props">
              <td>{{props.item.id}}</td>
              <td>{{props.item.identification_number}}</td>
              <td>{{props.item.name}}</td>
              <td>{{props.item.email}}</td>
              <td>
                <v-btn
                  class="text-lowercase"
                  icon
                  :href="`${protocol}//${props.item.subdomain}.${hostname}`"
                  target="_blank"
                >{{protocol}}//{{props.item.subdomain}}.{{hostname}}</v-btn>
              </td>
              <td>{{props.item.limit_documents | numberFormat}}</td>
              <td>
                <v-icon color="success" small @click="editItem(props.item)">edit</v-icon>
                <v-icon color="warning" small @click="sendItem(props.item)">delete</v-icon>
              </td>
            </template>
          </v-data-table>
        </v-card>
        <v-dialog v-model="dialog2" max-width="500px">
          <v-card>
            <v-card-title>
              <i class="fe fe-trash-2 fa-fw text-warning"></i>Eliminar
            </v-card-title>
            <v-card-text>Desea eliminar el registro?</v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="info" flat @click="dialog2 = false">Cancelar</v-btn>
              <v-btn
                color="warning"
                flat
                @click="deleteItem(company)"
                :loading="loadingDelete"
              >Eliminar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-app>
      <v-app id="company_form" style="height: 0px;">
        <v-layout row justify-center>
          <v-dialog
            v-model="dialog"
            persistent
            max-width="700"
            transition="dialog-bottom-transition"
          >
            <v-card>
              <v-card-title>
                <span class="headline">{{textModal}}</span>
              </v-card-title>
              <v-list three-line subheader>
                <v-form ref="form">
                  <v-container>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md12 lg12 xl12>
                        <h3>Datos de la Empresa</h3>
                      </v-flex>
                      <v-flex xs11 sm11 md5 lg5>
                        <v-text-field
                          v-model="identification_number"
                          v-validate="'required|numeric|max:15'"
                          :error-messages="errors.collect('identification_number')"
                          data-vv-name="identification_number"
                          :counter="15"
                          label="Número de identificación *"
                          :disabled="editMode"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs2 sm2 md2 lg2>
                        <v-text-field
                          v-model="dv"
                          v-validate="'required|numeric|max:1'"
                          :error-messages="errors.collect('dv')"
                          data-vv-name="dv"
                          :counter="1"
                          label="Dv *"
                          :disabled="editMode"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs11 sm11 md5 lg5>
                        <v-text-field
                          v-model="name"
                          v-validate="'required|max:50'"
                          :error-messages="errors.collect('name')"
                          data-vv-name="name"
                          :counter="50"
                          label="Empresa *"
                          :disabled="editMode"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field
                          v-model="subdomain"
                          v-validate="'required|alpha_dash|max:10'"
                          :suffix="`.${hostname}`"
                          :error-messages="errors.collect('subdomain')"
                          data-vv-name="subdomain"
                          :counter="10"
                          label="Subdominio *"
                          :disabled="editMode"
                          required
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm12 md12 lg12 xl12>
                        <h3>Datos Usuario</h3>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field
                          v-model="email"
                          v-validate="'required|email|max:50'"
                          :error-messages="errors.collect('email')"
                          data-vv-name="email"
                          :counter="50"
                          label="Correo electrónico *"
                          :disabled="editMode"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field
                          type="password"
                          v-model="password"
                          v-validate="'max:20'"
                          :error-messages="errors.collect('password')"
                          data-vv-name="password"
                          ref="password"
                          :counter="20"
                          label="Contraseña"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field
                          type="password"
                          v-model="password_confirmation"
                          v-validate="'confirmed:password'"
                          :error-messages="errors.collect('password_confirmation')"
                          data-vv-name="password_confirmation"
                          :counter="20"
                          label="Confirmar contraseña"
                          required
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm12 md12 lg12 xl12>
                        <h3>Datos Generales</h3>
                      </v-flex>

                      <v-flex xs12 sm12 md4 lg4>
                        <v-text-field
                          v-model="limit_documents"
                          v-validate="'required|numeric|max:11'"
                          :error-messages="errors.collect('limit_documents')"
                          data-vv-name="limit_documents"
                          :counter="11"
                          label="Límite de documentos *"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md4 lg4>
                        <v-text-field
                          v-model="economic_activity_code"
                          v-validate="'required'"
                          :error-messages="errors.collect('economic_activity_code')"
                          data-vv-name="economic_activity_code"
                          :counter="11"
                          label="Actividad economica *"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md4 lg4>
                        <v-text-field
                          v-model="ica_rate"
                          v-validate="'required'"
                          :error-messages="errors.collect('ica_rate')"
                          data-vv-name="ica_rate"
                          :counter="11"
                          label="Tasa ICA *"
                          required
                        ></v-text-field>
                      </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                      <!--<v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          required
                          data-vv-name="language_id"
                          v-validate="'required'"
                          v-model="language_id"
                          :error-messages="errors.collect('language_id')"
                          :items="language"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar lenguaje"
                        ></v-combobox>
                      </v-flex>-->
                      <!-- <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          required
                          data-vv-name="tax_id"
                          v-validate="'required'"
                          v-model="tax_id"
                          :error-messages="errors.collect('tax_id')"
                          :items="tax"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Impuesto"
                        ></v-combobox>
                      </v-flex>-->
                      <!--<v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          required
                          data-vv-name="type_environment_id"
                          v-validate="'required'"
                          v-model="type_environment_id"
                          :error-messages="errors.collect('type_environment_id')"
                          :items="type_enviroment"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Tipo Entorno"
                        ></v-combobox>
                      </v-flex>-->
                      <!-- <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          required
                          data-vv-name="type_operation_id"
                          v-validate="'required'"
                          v-model="type_operation_id"
                          :error-messages="errors.collect('type_operation_id')"
                          :items="type_operation"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Tipo Operacion"
                        ></v-combobox>
                      </v-flex>-->
                      <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          required
                          data-vv-name="type_document_identification_id"
                          v-validate="'required'"
                          v-model="type_document_identification_id"
                          :error-messages="errors.collect('type_document_identification_id')"
                          :items="type_documentation_identification"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Tipo Documentacion"
                        ></v-combobox>
                      </v-flex>
                      <!-- <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          data-vv-name="country_id"
                          v-validate="'required'"
                          @change="cascade('country')"
                          required
                          v-model="country_id"
                          :error-messages="errors.collect('country_id')"
                          :items="country"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Pais"
                        ></v-combobox>
                      </v-flex>-->
                      <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          data-vv-name="department_id"
                          v-validate="'required'"
                          @change="cascade"
                          required
                          v-model="department_id"
                          :error-messages="errors.collect('department_id')"
                          :items="deparments"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Departamento"
                        ></v-combobox>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          data-vv-name="municipality_id"
                          v-validate="'required'"
                          required
                          v-model="municipality_id"
                          :error-messages="errors.collect('municipality_id')"
                          :items="municipalities_filter"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Municipio"
                        ></v-combobox>
                      </v-flex>
                      <!--  <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          data-vv-name="type_currency_id"
                          v-validate="'required'"
                          required
                          v-model="type_currency_id"
                          :error-messages="errors.collect('type_currency_id')"
                          :items="type_currency"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Moneda"
                        ></v-combobox>
                      </v-flex>-->
                      <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          data-vv-name="type_organization_id"
                          v-validate="'required'"
                          required
                          v-model="type_organization_id"
                          :error-messages="errors.collect('type_organization_id')"
                          :items="type_organization"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Tipo Organizacion"
                        ></v-combobox>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          data-vv-name="type_regime_id"
                          v-validate="'required'"
                          required
                          v-model="type_regime_id"
                          :error-messages="errors.collect('type_regime_id')"
                          :items="type_regime"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Regimen"
                        ></v-combobox>
                      </v-flex>
                      <!-- <v-flex xs12 sm12 md6 lg6>
                        <v-combobox
                          data-vv-name="type_liability_id"
                          v-validate="'required'"
                          required
                          v-model="type_liability_id"
                          :error-messages="errors.collect('type_liability_id')"
                          :items="type_liability"
                          item-text="name"
                          item-value="id"
                          label="Seleccionar Responsabilidad"
                        ></v-combobox>
                      </v-flex>-->
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field
                          data-vv-name="merchant_registration"
                          v-validate="'required'"
                          v-model="merchant_registration"
                          :error-messages="errors.collect('merchant_registration')"
                          label="Registro mercantil"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field
                          data-vv-name="address"
                          v-validate="'required'"
                          v-model="address"
                          :error-messages="errors.collect('address')"
                          label="Direccion"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field
                          v-validate="'required|max:10|min:7'"
                          data-vv-name="phone"
                          v-model="phone"
                          :error-messages="errors.collect('phone')"
                          label="Telefono"
                          required
                        ></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="warning" flat @click="close">Cerrar</v-btn>
                    <v-btn color="bee" flat :loading="loading" @click="validate()">Guardar</v-btn>
                  </v-card-actions>
                </v-form>
              </v-list>
            </v-card>
          </v-dialog>
        </v-layout>
      </v-app>
    </div>
  </main>
</template>

<script>
import { resolve } from "q";
export default {
  props: {
    route: {
      required: true
    }
  },
  data: () => ({
    textModal: "",
    rules: [v => !!v || "The value is required"],
    protocol: window.location.protocol,
    hostname: window.location.hostname,
    columns: [
      {
        text: "#",
        value: "id"
      },
      {
        text: "Número de identificación",
        value: "identification_number"
      },
      {
        text: "Empresa",
        value: "name"
      },
      {
        text: "Correo electrónico",
        value: "email"
      },
      {
        text: "Subdominio",
        value: "subdomain"
      },
      {
        text: "Límite de documentos",
        value: "limit_documents"
      },
      {
        text: "Acciones",
        value: "actions",
        sortable: false
      }
    ],
    // form: {},
    companies: [],
    loading: false,
    dialog2: false,
    dialog: false,
    search: "",
    loadingDelete: false,
    loadDataTable: false,
    company: null,
    servicecompany: [],
    language: [],
    country: [],
    tax: [],
    type_enviroment: [],
    type_operation: [],
    type_documentation_identification: [],
    type_currency: [],
    type_organization: [],
    type_regime: [],
    type_liability: [],
    deparments: [],
    municipalities: [],
    municipalities_filter: [],
    identification_number: null,
    password_confirmation: null,
    limit_documents: null,
    subdomain: null,
    editMode: false,
    password: null,
    email: null,
    name: null,
    id: null,
    ica_rate: null,
    economic_activity_code: null,

    dv: 1,
    language_id: null,
    tax_id: null,
    type_environment_id: null,
    type_operation_id: null,
    type_document_identification_id: null,
    country_id: null,
    type_currency_id: null,
    type_organization_id: null,
    type_regime_id: null,
    type_liability_id: null,
    department_id: null,
    municipality_id: null,
    merchant_registration: null,
    address: null,
    phone: null,
    id_service: null
  }),
  filters: {
    numberFormat: value => numeral(value).format("0,0.00")
  },
  mounted() {
    this.refresh();
  },
  created() {
    this.tables();
    this.initForm();
  },
  methods: {
    toma() {
      console.log(1111);
    },
    cascade() {
      // this.muni
      let id_department = this.department_id.id;
      this.municipalities_filter = this.municipalities.filter(
        x => x.department_id == id_department
      );
    },
    initForm() {
      this.identification_number = null;
      this.password_confirmation = null;
      this.limit_documents = null;
      this.subdomain = null;
      this.editMode = false;
      this.password = null;
      this.email = null;
      this.name = null;
      this.id = null;

      this.language_id = null;
      this.tax_id = null;
      this.type_environment_id = null;
      this.type_operation_id = null;
      this.type_document_identification_id = null;
      this.country_id = null;
      this.type_currency_id = null;
      this.type_organization_id = null;
      this.type_regime_id = null;
      this.type_liability_id = null;
      this.department_id = null;
      this.municipality_id = null;
      this.merchant_registration = null;
      this.address = null;
      this.phone = null;
    },
    tables() {
      axios
        .get(`${this.route}Tables`)
        .then(response => {
          //console.log(response.data);
          this.language = response.data.language;
          // this.country = response.data.country;
          this.tax = response.data.tax;
          //  this.type_enviroment = response.data.type_enviroment;
          this.type_operation = response.data.type_operation;
          this.type_documentation_identification = response.data.type_documentation_identification;
          this.type_currency = response.data.type_currency;
          this.type_organization = response.data.type_organization;
          this.type_regime = response.data.type_regime;
          // this.type_liability = response.data.type_liability;

          this.deparments = response.data.deparments;
          this.municipalities = response.data.municipalities;
        })
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.response.data);
          this.$setLaravelErrors(error.response.data);
        })
        .then(() => {
          //this.loadDataTable = false;
        });
    },
    refresh() {
      this.loadDataTable = true;

      axios
        .post(`${this.route}All`)
        .then(response => {
          this.$setLaravelMessage(response.data);

          this.companies = response.data.company;
          this.servicecompany = response.data.servicecompany;
        })
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.response.data);
          this.$setLaravelErrors(error.response.data);
        })
        .then(() => {
          this.loadDataTable = false;
        });
    },

    constructForm() {
      return {
        identification_number: this.identification_number,
        password_confirmation: this.password_confirmation,
        limit_documents: this.limit_documents,
        subdomain: this.subdomain,
        //editMode: this.editMode,
        password: this.password,
        email: this.email,
        name: this.name,
        id: this.id,
        language_id: 79, //this.language_id.id,
        // tax_id: this.tax_id.id,
        //type_environment_id: this.type_environment_id.id,
        // type_operation_id: this.type_operation_id.id,
        type_document_identification_id: this.type_document_identification_id
          .id,
        country_id: 46,
        // type_currency_id: this.type_currency_id.id,
        type_organization_id: this.type_organization_id.id,
        type_regime_id: this.type_regime_id.id,
        type_liability_id: 14,
        department_id: this.department_id.id,
        municipality_id: this.municipality_id.id,
        merchant_registration: this.merchant_registration,
        address: this.address,
        phone: this.phone,
        dv: this.dv,
        id_service: this.id_service,

        economic_activity_code: this.economic_activity_code,
        ica_rate: this.ica_rate
      };
    },

    validate() {
      this.$validator.validateAll().then(valid => {
        if (valid) {
          this.loadDataTable = true;
          this.loading = true;

          if (this.editMode) {
            let obj = this.constructForm();
            axios
              .put(`${this.route}/${this.id}`, obj)
              .then(response => {
                this.$setLaravelMessage(response.data);

                if (response.data.success) {
                  this.dialog = false;

                  this.initForm();

                  this.refresh();
                }
              })
              .catch(error => {
                this.$setLaravelValidationErrorsFromResponse(
                  error.response.data
                );
                this.$setLaravelErrors(error.response.data);
              })
              .then(() => {
                this.loadDataTable = false;
                this.loading = false;
              });
          } else {
            let obj = this.constructForm();
            axios
              .post(this.route, obj)
              .then(response => {
                this.$setLaravelMessage(response.data);

                if (response.data.success) {
                  this.companies.push(response.data.company);

                  this.dialog = false;

                  this.initForm();
                }
              })
              .catch(error => {
                this.$setLaravelValidationErrorsFromResponse(
                  error.response.data
                );
                this.$setLaravelErrors(error.response.data);
              })
              .then(() => {
                this.loadDataTable = false;
                this.loading = false;
              });
          }
        }
      });
    },
    cleanForm() {
      this.identification_number = null;
      this.password_confirmation = null;
      this.limit_documents = null;
      this.subdomain = null;
      this.editMode = false;
      this.password = null;
      this.email = null;
      this.name = null;
      this.id = null;

      this.$validator.reset();
    },
    sendItem(company) {
      this.company = company;
      this.dialog2 = true;
    },
    editItem(company) {
      this.textModal = "Editar compañia";
      let contex = this;

      let serviceCompanyFound = this.servicecompany.find(
        x => x.identification_number == company.identification_number
      );

      this.identification_number = company.identification_number;
      this.limit_documents = company.limit_documents;
      this.subdomain = company.subdomain;
      this.email = company.email;
      this.name = company.name;
      this.id = company.id;

      this.editMode = true;

      if (serviceCompanyFound) {
        this.id_service = serviceCompanyFound.id;
        /* this.language_id = this.language.find(
          x => x.id == serviceCompanyFound.language_id
        );*/
        //  this.tax_id = this.tax.find(x => x.id == serviceCompanyFound.tax_id);
        /* this.type_environment_id = this.type_enviroment.find(
          x => x.id == serviceCompanyFound.type_environment_id
        );*/
        /* this.type_operation_id = this.type_operation.find(
          x => x.id == serviceCompanyFound.type_operation_id
        );*/
        this.type_document_identification_id = this.type_documentation_identification.find(
          x => x.id == serviceCompanyFound.type_document_identification_id
        );

        this.municipalities_filter = this.municipalities.filter(
          x => x.department_id == serviceCompanyFound.department_id
        );

        this.department_id = this.deparments.find(
          x => x.id == serviceCompanyFound.department_id
        );

        this.municipality_id = this.municipalities_filter.find(
          x => x.id == serviceCompanyFound.municipality_id
        );

        /* this.type_currency_id = this.type_currency.find(
          x => x.id == serviceCompanyFound.type_currency_id
        );*/
        this.type_organization_id = this.type_organization.find(
          x => x.id == serviceCompanyFound.type_organization_id
        );
        this.type_regime_id = this.type_regime.find(
          x => x.id == serviceCompanyFound.type_regime_id
        );
        /*this.type_liability_id = this.type_liability.find(
          x => x.id == serviceCompanyFound.type_liability_id
        );*/
        this.merchant_registration = serviceCompanyFound.merchant_registration;
        this.address = serviceCompanyFound.address;
        this.phone = serviceCompanyFound.phone;

        this.economic_activity_code = company.economic_activity_code
        this.ica_rate = company.ica_rate

      }

      this.dialog = true;
    },
    deleteItem(company) {
      this.loadDataTable = true;
      this.loadingDelete = true;
      axios
        .delete(`${this.route}/${company.id}`)
        .then(response => {
          this.$setLaravelMessage(response.data);

          this.companies.splice(
            this.companies.findIndex(row => row.id == company.id),
            1
          );
        })
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.response.data);
          this.$setLaravelErrors(error.response.data);
        })
        .then(() => {
          this.loadDataTable = false;
          this.loadingDelete = false;
          this.dialog2 = false;
          this.company = null;
          this.$eventHub.$emit("reloadData");
        });
    },
    close() {
      this.dialog = false;

      this.initForm();
    }
  }
};
</script>
