<template>
    <main class="content">
        <div class="container-fluid">
            <v-app id="items">
                <div class="content-header">
                    <h1>Listado de productos</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <v-btn @click="dialog = !dialog" class="bee darker text-white no-decoration">Nuevo producto</v-btn>
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
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="filterHeaders" :items="items":search="search" :loading="loadDataTable" class="elevation-1">
                        <v-progress-linear v-slot:progress color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td v-if="showColumn(columns, 'id')">{{props.item.id}}</td>
                            <td v-if="showColumn(columns, 'name')">{{props.item.name}}</td>
                            <td v-if="showColumn(columns, 'code')">{{props.item.code}}</td>
                            <td v-if="showColumn(columns, 'type_unit_id')">{{props.item.type_unit.name}}</td>
                            <td v-if="showColumn(columns, 'type_unit_id')">{{ratePrefix()}}{{props.item.price | numberFormat}}</td>
                            <td v-if="showColumn(columns, 'type_unit_id')">{{(props.item.tax != null) ? props.item.tax.name : null}}</td>
                            <td v-if="showColumn(columns, 'actions')">
                                <v-icon small color="info" class="mr-2" @click="editItem(props.item)">edit</v-icon>
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
                        <v-card-text>
                            Desea eliminar el registro?
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="info" flat @click="dialog2 = false">Cancelar</v-btn>
                            <v-btn color="warning" flat @click="deleteItem(product)" :loading="loadingDelete">Eliminar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-app>
            <v-app id="item_form" style="height: 0px;">
                <v-layout row justify-center>
                    <v-dialog v-model="dialog" persistent max-width="800" transition="dialog-bottom-transition">
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{title}}</span>
                            </v-card-title>
                            <v-list three-line subheader>
                                <v-form data-vv-scope="item">
                                    <v-container>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="item.name" class="input-uppercase" v-validate="'required|max:50'" :error-messages="errors.collect('item.name')" data-vv-name="name" :counter="50" label="Nombre *" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="item.code" class="input-uppercase" v-validate="'required|alpha_dash|max:11'" :error-messages="errors.collect('item.code')" data-vv-name="code" :counter="11" label="Código interno *" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-autocomplete v-model="item.type_unit_id" v-validate="'required|max:255'" :error-messages="errors.collect('item.type_unit_id')" data-vv-name="type_unit_id" :counter="255" :items="typeUnits" item-text="name" item-value="id" label="Unidad *" required></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="item.price" v-validate="'required|decimal:2|max:13'" :prefix="currencySymbol" :error-messages="errors.collect('item.price')" data-vv-name="price" :counter="13" label="Precio unitario (Venta base) *" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-autocomplete v-model="item.tax_id" v-validate="'max:255'" :error-messages="errors.collect('item.tax_id')" data-vv-name="tax_id" :counter="255" :items="taxes" item-text="name" item-value="id" label="Impuesto"></v-autocomplete>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="warning" flat @click="dialog = false">Cerrar</v-btn>
                                        <v-btn color="bee" flat :loading="loading" @click="validate('item')" class="text-white">Guardar</v-btn>
                                    </v-card-actions>
                                </v-form>
                            </v-list>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </v-app>
            <tenant-import-import title="Importar productos" :route="route" :dialogImport.sync="dialogImport" @refresh="refreshData"></tenant-import-import>
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
            loadingDelete: false,
            loadDataTable: false,
            dialogImport: false,
            editMode: false,
            loading: false,
            dialog: false,
            dialog2: false,
            typeUnits: [],
            search: '',
            taxes: [],
            items: [],
            item: {},
            product: null,
            columns: [{
                text: '#',
                value: 'id',
                canHide: false,
                show: true
            }, {
                text: 'Nombre',
                value: 'name',
                canHide: false,
                show: true
            }, {
                text: 'Código interno',
                value: 'code',
                canHide: false,
                show: true
            }, {
                text: 'Unidad',
                value: 'type_unit_id',
                canHide: false,
                show: true
            }, {
                text: 'Precio unitario',
                value: 'price',
                canHide: false,
                show: true
            }, {
                text: 'Impuesto',
                value: 'tax_id',
                canHide: false,
                show: true
            }, {
                text: 'Acciones',
                value: 'actions',
                sortable: false,
                canHide: true,
                show: true
            }]
        }),
        computed: {
            filterHeaders() {
                return this.columns.filter(column => column.show);
            },
            title() {
                return (this.editMode) ? 'Edición producto' : 'Nuevo producto';
            },
            urlData() {
                return (this.editMode) ? {method: 'put', url: `${this.route}/${this.item.id}`} : {method: 'post', url: this.route};
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
                this.item = {
                    type_unit_id: 10
                };
            },
            refreshData() {
                this.loadDataTable = true;

                axios.post(`${this.route}All`).then(response => {
                    this.$setLaravelMessage(response.data);

                    this.typeUnits = response.data.typeUnits;
                    this.taxes = response.data.taxes;
                    this.items = response.data.items;
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
            validate(scope) {
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) {
                        this.loading = true;

                        axios({method: this.urlData.method, url: this.urlData.url, data: this.item}).then(response => {
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
            editItem(item) {
                this.item = JSON.parse(JSON.stringify(item));
                this.editMode = true;
                this.dialog = true;
            },
            sendItem(product) {
                this.product = product;
                this.dialog2 = true
            },
            deleteItem(item) {
                axios.delete(`${this.route}/${item.id}`).then(response => {
                    this.$setLaravelMessage(response.data);

                    this.items.splice(this.items.findIndex(row => row.id == item.id), 1);
                }).catch(error => {
                    this.$setLaravelValidationErrorsFromResponse(error.response.data);
                    this.$setLaravelErrors(error.response.data);
                }).then(() => {
                    this.loadDataTable = false;
                    this.loadingDelete = false;
                    this.dialog2 = false;
                    this.product = null;
                    this.$eventHub.$emit('reloadData')
                });
            },
            downloadExport() {
                window.open(`${this.route}/export`, '_blank');
            }
        }
    }
</script>

<style lang="scss">
    .input-uppercase input {
        text-transform: uppercase
    }
</style>
