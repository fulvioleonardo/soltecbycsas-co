<template>
    <main class="content">
        <div class="container-fluid">
            <v-app id="taxes">
                <div class="content-header">
                    <h1>Listado de impuestos</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <v-btn @click="dialog = !dialog" class="bee darker text-white no-decoration">Nuevo impuesto</v-btn>
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
                    </v-card-title>
                    <v-data-table :headers="columns" :items="taxes" :search="search" :loading="loadDataTable"  class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{props.item.id}}</td>
                            <td>{{props.item.name}}</td>
                            <td>{{props.item.code}}</td>
                            <td>{{ratePrefix(props.item)}}{{props.item.rate | numberFormat}}{{rateSuffix(props.item)}}</td>
                            <td>{{props.item.conversion | numberFormat}}</td>
                            <td>{{props.item.is_percentage}}</td>
                            <td>{{props.item.is_fixed_value}}</td>
                            <td>{{props.item.is_retention}}</td>
                            <td>{{props.item.in_base}}</td>
                            <td>{{(props.item.hasOwnProperty('tax') && (props.item.tax != null)) ? props.item.tax.name : null}}</td>
                            <td>
                                <v-icon small class="mr-2" @click="editItem(props.item)">edit</v-icon>
                                <v-icon small @click="deleteItem(props.item)">delete</v-icon>
                            </td>
                            <td>
                                {{ props.item.type_tax.name }}
                            </td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-app>
            <v-app id="tax_form" style="height: 0px;">
                <v-layout row justify-center>
                    <v-dialog v-model="dialog" persistent max-width="750" transition="dialog-bottom-transition">
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{title}}</span>
                            </v-card-title>
                            <v-list three-line subheader>
                                <v-form data-vv-scope="tax">
                                    <v-container>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="tax.name" class="input-uppercase" v-validate="'required|max:30'" :error-messages="errors.collect('tax.name')" data-vv-name="name" :counter="30" label="Nombre *" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="tax.code" v-validate="'alpha_dash|max:2'" :error-messages="errors.collect('tax.code')" data-vv-name="code" :counter="2" label="Código"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="tax.rate" v-validate="'decimal:2|max:13'" :prefix="ratePrefix(tax)" :suffix="rateSuffix(tax)" :error-messages="errors.collect('tax.rate')" data-vv-name="rate" :counter="13" label="Tasa"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-text-field v-model="tax.conversion" v-validate="'decimal:2|max:7'" :error-messages="errors.collect('tax.conversion')" data-vv-name="conversion" :counter="7" label="Conversión"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-switch v-model="tax.is_percentage" v-validate="'required'" :error-messages="errors.collect('tax.is_percentage')" data-vv-name="is_percentage" label="Tasa porcentaje" @change="validateRate(tax.is_percentage)" required></v-switch>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-switch v-model="tax.is_fixed_value" v-validate="'required'" :error-messages="errors.collect('tax.is_fixed_value')" data-vv-name="is_fixed_value" label="Tasa valor fijo" @change="validateRate(tax.is_fixed_value, false)" required></v-switch>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-switch v-model="tax.is_retention" v-validate="'required'" :error-messages="errors.collect('tax.is_retention')" data-vv-name="is_retention" label="Impuesto de retención" required></v-switch>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-switch v-model="tax.in_base" v-validate="'required'" :error-messages="errors.collect('tax.in_base')" data-vv-name="in_base" label="Retención en base" required></v-switch>
                                            </v-flex>
                                            <v-flex xs12 sm12 md6 lg4>
                                                <v-autocomplete v-model="tax.in_tax" v-validate="`${inTax.validate}`" :error-messages="errors.collect('tax.in_tax')" data-vv-name="in_tax" :counter="255" :items="taxesAutocomplete" item-text="name" item-value="id" :label="inTax.label" :disabled="inTax.disabled"></v-autocomplete>
                                            </v-flex>
                                             <v-flex xs12 sm12 md6 lg4>
                                                <v-autocomplete v-model="tax.type_tax_id" v-validate="'required'" :error-messages="errors.collect('tax.type_tax_id')" data-vv-name="type_tax_id" :counter="255" :items="type_taxes" item-text="name" item-value="id" label="Tipo Impuesto"></v-autocomplete>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="warning" flat @click="dialog = false">Cerrar</v-btn>
                                        <v-btn color="bee" flat :loading="loading" @click="validate('tax')" class="text-white">Guardar</v-btn>
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
    import Helper from '../../../mixins/Helper';
    
    export default {
        mixins: [Helper],
        props: {
            route: {
                required: true
            }
        },
        data: () => ({
            taxesAutocomplete: [],
            loadDataTable: false,
            editMode: false,
            loading: false,
            dialog: false,
            search: '',
            columns: [{
                text: '#',
                value: 'id'
            }, {
                text: 'Nombre',
                value: 'name'
            }, {
                text: 'Código',
                value: 'code'
            }, {
                text: 'Tasa',
                value: 'rate'
            }, {
                text: 'Conversión',
                value: 'conversion'
            }, {
                text: 'Tasa porcentaje',
                value: 'is_percentage'
            }, {
                text: 'Tasa valor fijo',
                value: 'is_fixed_value'
            }, {
                text: 'Impuesto de retención',
                value: 'is_retention'
            }, {
                text: 'Retención en base',
                value: 'in_base'
            }, {
                text: 'Retención en impuesto',
                value: 'in_tax'
            }, {
                text: 'Acciones',
                value: 'actions',
                sortable: false
            },{
                text: 'Tipo Impuesto',
                value: 'type_tax',
                sortable: false
            }
            ],
            taxes: [],
            tax: {},
            type_taxes: []
        }),
        computed: {
            title() {
                return (this.editMode) ? 'Edición impuesto' : 'Nuevo impuesto';
            },
            urlData() {
                return (this.editMode) ? {method: 'put', url: `${this.route}/${this.tax.id}`} : {method: 'post', url: this.route};
            },
            inTax() {
                return ((this.tax.is_retention) && (!this.tax.in_base)) ? {validate: 'required|max:255', label: 'Retención en impuesto *', disabled: false} : {validate: 'max:255', label: 'Retención en impuesto', disabled: true};
            }
        },
        mounted() {
            this.cleanForm();
            
            this.refreshData();
        },
        methods: {
            cleanForm() {
                this.tax.is_retention = false;
                this.tax.in_base = false;
                this.tax.in_tax = null;
                this.editMode = false;
                this.tax = {
                    is_fixed_value: false,
                    is_retention: false,
                    is_percentage: true,
                    conversion: 100,
                    in_base: false,
                    in_tax: null,
                    code: null,
                    name: null
                };
                
                this.errors.clear();
            },
            validateRate(val, isPercentage = true) {
                if (isPercentage) this.tax.is_fixed_value = !val;
                if (!isPercentage) this.tax.is_percentage = !val;
            },
            refreshData() {
                this.loadDataTable = true;
                
                axios.post(`${this.route}All`).then(response => {
                    this.$setLaravelMessage(response.data);
                    
                    this.taxesAutocomplete = response.data.taxes;
                    this.taxes = response.data.taxes;
                    this.type_taxes = response.data.type_taxes
                }).catch(error => {
                    this.$setLaravelValidationErrorsFromResponse(error.response.data);
                    this.$setLaravelErrors(error.response.data);
                }).then(() => {
                    this.loadDataTable = false;
                });
            },
            close() {
                this.taxesAutocomplete = this.taxes;
                
                this.dialog = false;
                
                this.cleanForm();
            },
            validate(scope) {
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) {
                        this.loading = true;
                        
                        axios({method: this.urlData.method, url: this.urlData.url, data: this.tax}).then(response => {
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
            editItem(tax) {
                this.taxesAutocomplete = this.taxesAutocomplete.filter(row => row.id != tax.id);
                this.editMode = true;
                this.dialog = true;
                this.tax = JSON.parse(JSON.stringify(tax));
            },
            deleteItem(tax) {
                axios.delete(`${this.route}/${tax.id}`).then(response => {
                    this.$setLaravelMessage(response.data);
                    
                    this.taxes.splice(this.taxes.findIndex(row => row.id == tax.id), 1);
                }).catch(error => {
                    this.$setLaravelValidationErrorsFromResponse(error.response.data);
                    this.$setLaravelErrors(error.response.data);
                }).then(() => {});
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

