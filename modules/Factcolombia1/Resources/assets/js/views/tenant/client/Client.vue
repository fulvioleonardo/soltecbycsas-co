<template>
    <main class="content">
        <div class="container-fluid">
            <v-app id="clients">
                <div class="content-header">
                    <h1>Listado de clientes</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <v-btn class="bee darker text-white no-decoration" @click="dialog = !dialog">Nuevo cliente</v-btn>
                            </li>
                            <li class="breadcrumb-item">
                                <v-btn icon class="bee darker text-white no-decoration" @click="dialogImport = !dialogImport">
                                    <v-icon small>fas fa-file-import</v-icon>
                                </v-btn>
                            </li>
                            <li class="breadcrumb-item">
                                <v-btn icon class="bee darker text-white no-decoration" @click="downloadExport">
                                    <v-icon small>fas fa-file-download</v-icon>
                                </v-btn>
                            </li>
                        </ol>
                    </nav>
                </div>
                <v-card>
                    <v-card-title>
                        <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-menu :close-on-content-click="false" offset-y transition="v-scale-transition">
                            <template v-slot:activator="{on}">
                                <button v-on="on" class="grey lighten-2 btn-sm">Mostrar/Ocultar Columnas</button>
                            </template>
                            <v-list>
                                <v-list-tile v-for="(column, index) in columnsShort" :key="index"> <!-- sin sortable -->
                                    <v-checkbox :label="column.text" v-model="column.show" :disabled="column.canHide"></v-checkbox>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </v-card-title>
                    <v-data-table :headers="filterHeaders" :items="clients" :search="search" :loading="loadDataTable" class="elevation-1">
                        <v-progress-linear v-slot:progress color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td v-if="showColumn(columns, 'id')">{{props.item.id}}</td>
                            <td v-if="showColumn(columns, 'type_person_id')">{{(props.item.type_person != null) ? props.item.type_person.name : null}}</td>
                            <td v-if="showColumn(columns, 'type_regime_id')">{{(props.item.type_regime != null) ? props.item.type_regime.name : null}}</td>
                            <td v-if="showColumn(columns, 'type_identity_document_id')">{{(props.item.type_identity_document != null) ? props.item.type_identity_document.name : null}}</td>
                            <td v-if="showColumn(columns, 'identification_number')">{{props.item.identification_number}}</td>
                            <td v-if="showColumn(columns, 'name')">{{props.item.name}}</td>
                            <td v-if="showColumn(columns, 'country_id')">{{(props.item.country != null) ? props.item.country.name : null}}</td>
                            <td v-if="showColumn(columns, 'department_id')">{{(props.item.department != null) ? props.item.department.name : null}}</td>
                            <td v-if="showColumn(columns, 'city_id')">{{(props.item.city != null) ? props.item.city.name : null}}</td>
                            <td v-if="showColumn(columns, 'address')">{{props.item.address}}</td>
                            <td v-if="showColumn(columns, 'phone')">{{props.item.phone}}</td>
                            <td v-if="showColumn(columns, 'email')">{{props.item.email}}</td>
                            <td v-if="showColumn(columns, 'actions')">
                                <v-icon small color="info" class="mr-2" @click="editItem(props.item)">edit</v-icon>
                                <v-icon small color="warning" @click="sendItem(props.item)">delete</v-icon>
                            </td>
                        </template>
                    </v-data-table>
                </v-card>
                <v-dialog v-model="dialog2" max-width="500px">
                    <v-card>
                        <v-card-title>
                            <i class="fe fe-trash-2 fa-fw text-warning"></i>Eliminar
                        </v-card-title>
                        <v-card-text>
                            Desea eliminar el registro?
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="info" flat @click="dialog2 = false">Cancelar</v-btn>
                            <v-btn color="warning" flat @click="deleteItem(clientDelete)" :loading="loadingDelete">Eliminar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-app>
            <v-app id="client_form" style="height: 0px;">
                <v-layout row justify-center>
                    <v-dialog v-model="dialog" persistent max-width="1000" transition="dialog-bottom-transition">
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{title}}</span>
                            </v-card-title>
                            <v-list three-line subheader>
                                <v-form data-vv-scope="client">
                                    <v-container>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm12 md3 lg2>
                                                <v-autocomplete v-model="client.type_person_id" v-validate="'max:255'" :error-messages="errors.collect('client.type_person_id')" data-vv-name="type_person_id" :counter="255" :items="typePeople" item-text="name" item-value="id" label="Tipo de persona"></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-autocomplete v-model="client.type_regime_id" v-validate="'max:255'" :error-messages="errors.collect('client.type_regime_id')" data-vv-name="type_regime_id" :counter="255" :items="typeRegimes" item-text="name" item-value="id" label="Tipo de régimen"></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-autocomplete v-model="client.type_identity_document_id" v-validate="'required|max:255'" :error-messages="errors.collect('client.type_identity_document_id')" data-vv-name="type_identity_document_id" :counter="255" :items="typeIdentityDocuments" item-text="name" item-value="id" label="Tipo identificacion*" required :disabled="editMode"></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm12 md2 lg2>
                                                <v-text-field v-model="client.dv" v-validate="'required|max:1'" :error-messages="errors.collect('client.dv')" data-vv-name="dv" :counter="1" label="DV"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="client.identification_number" v-validate="'required|numeric|max:15'" :error-messages="errors.collect('client.identification_number')" data-vv-name="identification_number" :counter="15" label="Número de identificación *" required :disabled="editMode"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="client.name" v-validate="'required|max:50'" :error-messages="errors.collect('client.name')" data-vv-name="name" :counter="50" label="Nombre / Razon social*" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4 xl4>
                                                <v-autocomplete v-model="client.country_id" v-validate="'max:255'" :error-messages="errors.collect('client.country_id')" data-vv-name="country_id" :counter="255" :items="countries" item-text="name" item-value="id" label="Pais" @change="departmentss"></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4 xl4>
                                                <v-autocomplete v-model="client.department_id" v-validate="'max:255'" :error-messages="errors.collect('client.department_id')" data-vv-name="department_id" :counter="255" :items="departments" item-text="name" item-value="id" label="Departamento" @change="citiess"></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4 xl4>
                                                <v-autocomplete v-model="client.city_id" v-validate="'max:255'" :error-messages="errors.collect('client.city_id')" data-vv-name="city_id" :counter="255" :items="cities" item-text="name" item-value="id" label="Ciudad"></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4 xl4>
                                                <v-text-field v-model="client.address" v-validate="'max:50'" :error-messages="errors.collect('client.address')" data-vv-name="address" :counter="50" label="Dirección"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4 xl4>
                                                <v-text-field v-model="client.phone" v-validate="'integer|max:10'" :error-messages="errors.collect('client.phone')" data-vv-name="phone" :counter="10" label="Teléfono"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="client.email" v-validate="'email|max:50'" :error-messages="errors.collect('client.email')" data-vv-name="email" :counter="50" label="Correo electrónico"></v-text-field>
                                            </v-flex>
                                             <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="client.code" class="input-uppercase" v-validate="'required|alpha_dash|max:11'" :error-messages="errors.collect('client.code')" data-vv-name="code" :counter="11" label="Código interno *" required></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="warning" flat @click="dialog = false">Cerrar</v-btn>
                                        <v-btn color="bee" flat :loading="loading" @click="validate('client')" class="text-white">Guardar</v-btn>
                                    </v-card-actions>
                                </v-form>
                            </v-list>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </v-app>
            <tenant-import-import title="Importar clientes" :route="route" :dialogImport.sync="dialogImport" @refresh="refreshData"></tenant-import-import>
        </div>
    </main>
</template>

<script>
    import Helper from '../../../mixins/Helper';

    export default {
        mixins: [Helper],
        props: {
            route: {
                required: true
            }
        },
        data: () => ({
            typeIdentityDocuments: [],
            loadDataTable: false,
            dialogImport: false,
            editMode: false,
            typeRegimes: [],
            search: '',
            departments: [],
            loading: false,
            typePeople: [],
            dialog: false,
            countries: [],
            clients: [],
            cities: [],
            client: {},
            clientDelete: null,
            loadingDelete: false,
            dialog2: false,
            columns: [{
                text: '#',
                value: 'id',
                canHide: false,
                show: true,
            }, {
                text: 'Tipo de persona',
                value: 'type_person_id',
                canHide: false,
                show: false,
                default: true
            }, {
                text: 'Tipo de régimen',
                value: 'type_regime_id',
                canHide: false,
                show: false,
                default: true
            }, {
                text: 'Tipo documento de identidad',
                value: 'type_identity_document_id',
                canHide: false,
                show: true
            }, {
                text: 'Número de identificación',
                value: 'identification_number',
                canHide: false,
                show: true
            }, {
                text: 'Nombre',
                value: 'name',
                canHide: false,
                show: true
            }, {
                text: 'Pais',
                value: 'country_id',
                canHide: false,
                show: false,
                default: true
            }, {
                text: 'Departamento',
                value: 'department_id',
                canHide: false,
                show: false,
                default: true
            }, {
                text: 'Ciudad',
                value: 'city_id',
                canHide: false,
                show: false,
                default: true
            }, {
                text: 'Dirección',
                value: 'address',
                canHide: false,
                show: false,
                default: true
            }, {
                text: 'Teléfono',
                value: 'phone',
                canHide: false,
                show: true,
                default: true
            }, {
                text: 'Correo electrónico',
                value: 'email',
                canHide: false,
                show: true,
                default: true
            }, {
                text: 'Acciones',
                value: 'actions',
                sortable: false,
                canHide: true,
                show: true
            }],
        }),
        computed: {
            filterHeaders() {
                return this.columns.filter(column => column.show);
            },
            title() {
                return (this.editMode) ? 'Edición cliente' : 'Nuevo cliente';
            },
            urlData() {
                return (this.editMode) ? {method: 'put', url: `${this.route}/${this.client.id}`} : {method: 'post', url: this.route};
            },
            columnsShort() {
                return this.columns.filter(column => column.default);
            }
        },
        mounted() {
            this.cleanForm();

            this.refreshData();
        },
        methods: {
            cleanForm() {
                this.$validator.reset();
                this.editMode = false;
                this.client = {};

                this.departmentss();
                this.citiess();
            },
            refreshData() {
                this.loadDataTable = true;

                axios.post(`${this.route}All`).then(response => {
                    this.$setLaravelMessage(response.data);

                    this.typeIdentityDocuments = response.data.typeIdentityDocuments;
                    this.typeRegimes = response.data.typeRegimes;
                    this.typePeople = response.data.typePeople;
                    this.countries = response.data.countries;
                    this.clients = response.data.clients;
                }).catch(error => {
                    this.$setLaravelValidationErrorsFromResponse(error.response.data);
                    this.$setLaravelErrors(error.response.data);
                }).then(() => {
                    this.loadDataTable = false;
                });
            },
            close() {
                this.dialog = false;

                this.cleanForm();
            },
            departmentss(edit = false) {
                if (!edit) {
                    this.client.department_id = null;
                    this.client.city_id = null;
                    this.departments = [];
                    this.cities = [];
                }

                if (this.client.country_id != null) this.getDepartment(this.client.country_id).then(rows => this.departments = rows);
            },
            citiess(edit = false) {
                if (!edit) {
                    this.client.city_id = null;
                    this.cities = [];
                }

                if (this.client.department_id != null) this.getCities(this.client.department_id).then(rows => this.cities = rows);
            },
            validate(scope) {
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) {
                        this.loading = true;

                        axios({method: this.urlData.method, url: this.urlData.url, data: this.client}).then(response => {
                            this.$setLaravelMessage(response.data);

                            if (response.data.success) {
                                this.refreshData();

                                this.dialog = false;

                                this.cleanForm();
                            }
                        }).catch(error => {
                            this.$setLaravelValidationErrorsFromResponse(error.response.data);
                            this.$setLaravelErrors(error.response.data);
                        }).then(() => {
                            this.loading = false;
                        });
                    }
                });
            },
            editItem(client) {
                this.client = JSON.parse(JSON.stringify(client));
                this.editMode = true;
                this.dialog = true;

                this.departmentss(true);
                this.citiess(true);
            },
            sendItem(client) {
                this.clientDelete = client;
                this.dialog2 = true
            },
            deleteItem(client) {
                axios.delete(`${this.route}/${client.id}`).then(response => {
                    this.$setLaravelMessage(response.data);

                    this.clients.splice(this.clients.findIndex(row => row.id == client.id), 1);
                }).catch(error => {
                    this.$setLaravelValidationErrorsFromResponse(error.response.data);
                    this.$setLaravelErrors(error.response.data);
                }).then(() => {
                    this.loadDataTable = false;
                    this.loadingDelete = false;
                    this.dialog2 = false;
                    this.clientDelete = null;
                });
            },
            downloadExport() {
                window.open(`${this.route}/export`, '_blank');
            }
        }
    }
</script>
