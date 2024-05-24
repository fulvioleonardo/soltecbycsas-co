<template>
    <el-dialog width="60%" :title="titleDialog" :visible="showDialog" @close="close" @open="create" @opened="opened" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <!-- <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre <span class="text-danger">*</span></label>
                            <el-input v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.trade_name}">
                            <label class="control-label">Nombre comercial</label>
                            <el-input v-model="form.trade_name" ></el-input>
                            <small class="form-control-feedback" v-if="errors.trade_name" v-text="errors.trade_name[0]"></small>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="row" v-if="type === 'customers'">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.person_type_id}">
                            <label class="control-label">Tipo de cliente</label>
                            <el-select v-model="form.person_type_id" filterable  >
                                <el-option v-for="option in person_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.person_type_id" v-text="errors.person_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group"  >
                            <label class="control-label">Comentario</label>
                            <el-input v-model="form.comment"></el-input>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="row">
                    <div class="col-md-6" v-if="form.state">
                        <div class="form-group" >
                            <label class="control-label">Estado del Contribuyente</label>
                            <template v-if="form.state == 'ACTIVO'">
                                <el-alert   :title="`${form.state}`"  type="success"   show-icon :closable="false"></el-alert>
                            </template>
                            <template v-else>
                                <el-alert   :title="`${form.state}`"  type="error"   show-icon :closable="false"></el-alert>
                            </template>
                        </div>

                    </div>
                    <div class="col-md-6" v-if="form.condition">
                        <div class="form-group" >
                            <label class="control-label">Condición del Contribuyente</label>
                            <template v-if="form.condition == 'HABIDO'">
                                <el-alert   :title="`${form.condition}`"  type="success"   show-icon :closable="false"></el-alert>
                            </template>
                            <template v-else>
                                <el-alert   :title="`${form.condition}`"  type="error"   show-icon :closable="false"></el-alert>
                            </template>
                        </div>

                    </div>
                </div> -->

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.type_person_id}">
                            <label class="control-label">Tipo de persona</label>
                            <el-select v-model="form.type_person_id" filterable>
                                <el-option v-for="option in type_persons" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_person_id" v-text="errors.type_person_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.type_regime_id}">
                            <label class="control-label">Tipo de régimen</label>
                            <el-select v-model="form.type_regime_id" filterable>
                                <el-option v-for="option in type_regimes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_regime_id" v-text="errors.type_regime_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.identity_document_type_id}">
                            <label class="control-label">Tipo de identificación</label>
                            <el-select v-model="form.identity_document_type_id"  filterable>
                                <el-option v-for="option in identity_document_types" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.identity_document_type_id" v-text="errors.identity_document_type_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.type_obligation_id}">
                            <label class="control-label">Tipo de obligación</label>
                            <el-select v-model="form.type_obligation_id"  filterable>
                                <el-option v-for="option in type_obligations" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_obligation_id" v-text="errors.type_obligation_id[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-5">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label class="control-label">N° Identificación  </label>
                            <!--<el-input @change="changeNumberIdentification" v-model="form.number" ></el-input>-->
                            <el-input v-model="form.number" :maxlength="maxLength" dusk="number">
                                <el-button type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="changeNumberIdentification">
                                </el-button>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" :class="{'has-danger': errors.dv}">
                            <label class="control-label">Dv  </label>
                            <el-input v-model="form.dv" ></el-input>
                            <small class="form-control-feedback" v-if="errors.dv" v-text="errors.dv[0]"></small>
                        </div>
                    </div>

                     <div class="col-md-5">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre  </label>
                            <el-input v-model="form.name" ></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.country_id}">
                            <label class="control-label">País</label>
                            <el-select v-model="form.country_id" filterable @change="departmentss()">
                                <el-option v-for="option in countries" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.country_id" v-text="errors.country_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.department_id}">
                            <label class="control-label">Departamento</label>
                            <el-select v-model="form.department_id" filterable @change="citiess()">
                                <el-option v-for="option in departments" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.department_id" v-text="errors.department_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.city_id}">
                            <label class="control-label">Ciudad</label>
                            <el-select v-model="form.city_id" filterable >
                                <el-option v-for="option in cities" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.city_id" v-text="errors.city_id[0]"></small>
                        </div>
                    </div>

                </div>

                <div class="row">
                     <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.telephone}">
                            <label class="control-label">Teléfono</label>
                            <el-input type="tel" maxlength="10" v-model="form.telephone" onkeydown="return ( event.ctrlKey || event.altKey
                                                                                                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false)
                                                                                                    || (95<event.keyCode && event.keyCode<106)
                                                                                                    || (event.keyCode==8) || (event.keyCode==9)
                                                                                                    || (event.keyCode>34 && event.keyCode<40)
                                                                                                    || (event.keyCode==46) )"></el-input>
                            <small class="form-control-feedback" v-if="errors.telephone" v-text="errors.telephone[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo electrónico</label>
                            <el-input v-model="form.email" dusk="email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>

                </div>

                <div class="row">
                     <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.address}">
                            <label class="control-label">Dirección</label>
                            <el-input v-model="form.address" dusk="address"></el-input>
                            <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="form-group" :class="{'has-danger': errors.code}">
                            <label class="control-label">Código interno  </label>
                            <el-input v-model="form.code" ></el-input>
                            <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
                        </div>
                    </div>
                </div>




                <div class="row mt-2" v-if="type === 'suppliers'">


                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.postal_code}">
                            <label class="control-label">Código postal  </label>
                            <el-input v-model="form.postal_code" maxlength="8"></el-input>
                            <small class="form-control-feedback" v-if="errors.postal_code" v-text="errors.postal_code[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6 center-el-checkbox">
                        <div class="form-group" :class="{'has-danger': errors.perception_agent}">
                            <el-checkbox v-model="form.perception_agent">¿Es agente de percepción?</el-checkbox><br>
                            <small class="form-control-feedback" v-if="errors.perception_agent" v-text="errors.perception_agent[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6" v-if="type === 'suppliers'" v-show="form.perception_agent">
                        <div class="form-group"  >
                            <label class="control-label">Porcentaje de percepción</label>

                            <el-input v-model="form.percentage_perception"></el-input>
                        </div>
                    </div>
                </div>

                <div class="row border-top mt-2">
                    <div class="col-12">
                        <h4>Contacto</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" >
                            <label class="control-label">Nombre y Apellido</label>
                            <el-input v-model="form.contact_name"></el-input>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" >
                            <label class="control-label">Teléfono</label>
                            <el-input v-model="form.contact_phone"></el-input>
                        </div>
                    </div>
                </div>
                <!-- <div class="row m-t-10">
                    <div class="col-md-12 text-center">
                        <el-button size="mini" icon="el-icon-plus" @click.prevent="clickAddAddress()">Agregar dirección</el-button>
                    </div>
                </div> -->
                <!-- <div class="row m-t-10" v-for="(row, index) in form.addresses">
                    <div class="col-md-12">
                        <label class="control-label" v-if="index === 0">
                            Dirección principal
                        </label>
                        <label class="control-label" v-else>
                            Dirección secundaria # {{ index }}
                            <el-button size="mini" icon="el-icon-minus" @click.prevent="clickRemoveAddress(index)" class="btn-default-danger">Eliminar dirección</el-button>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.country_id}">
                            <label class="control-label">País</label>
                            <el-select v-model="row.country_id" filterable>
                                <el-option v-for="option in countries" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.country_id" v-text="errors.country_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.location_id}">
                            <label class="control-label">Ubigeo</label>
                            <el-cascader :options="locations" v-model="row.location_id" :clearable="true" filterable></el-cascader>
                            <small class="form-control-feedback" v-if="errors.location_id" v-text="errors.location_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.address}">
                            <label class="control-label">Dirección</label>
                            <el-input v-model="row.address"></el-input>
                            <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.phone}">
                            <label class="control-label">Teléfono</label>
                            <el-input v-model="row.phone"></el-input>
                            <small class="form-control-feedback" v-if="errors.phone" v-text="errors.phone[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo electrónico</label>
                            <el-input v-model="row.email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    import { calcularDv } from '../../../functions/Nit';


    // import Helper from '@assetsModuleProColombia/mixins/Helper';

    export default {
        // mixins: [Helper],
        props: ['showDialog', 'type', 'recordId', 'external', 'document_type_id','input_person'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'persons',
                errors: {},
                api_service_token:false,
                form: {},
                countries: [],
                all_departments: [],
                all_provinces: [],
                all_districts: [],
                provinces: [],
                districts: [],
                locations: [],
                person_types: [],
                identity_document_types: [],
                type_persons: [],
                type_regimes: [],
                type_obligations: [],
                countries: [],
                departments: [],
                cities: [],
                loading_search: false
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.api_service_token = response.data.api_service_token
                    // console.log(this.api_service_token)

                    this.countries = response.data.countries
                    // this.all_departments = response.data.departments;
                    // this.all_provinces = response.data.provinces;
                    // this.all_districts = response.data.districts;
                    this.identity_document_types = response.data.identity_document_types;
                    // this.locations = response.data.locations;
                    this.person_types = response.data.person_types;

                    this.type_persons = response.data.typePeople
                    this.type_regimes = response.data.typeRegimes
                    this.type_obligations = response.data.typeObligations
                    this.identity_document_types = response.data.typeIdentityDocuments
                    // this.countries = response.data.countries

                })
        },
        computed: {
            maxLength: function () {
                if (this.form.identity_document_type_id === '6') {
                    return 11
                }
                if (this.form.identity_document_type_id === '1') {
                    return 8
                }
            }
        },
        methods: {
            async changeNumberIdentification()
            {
                if(this.form.number)
                {
                    this.loading_search = true
                    this.form.dv = await calcularDv(this.form.number)
                    await this.searchNameClient()
                    this.loading_search = false

                }

            },
            async searchNameClient()
            {
                if(this.form.number.length < 8)
                {
                    return
                }
                await this.$http.get(`/${this.resource}/searchName/${this.form.number}`).then(response => {

                    if(response.data.data)
                    {
                        this.form.name = response.data.data
                    }

                }).catch(error => {

                }).then(() => {
                });
            },
            getDepartment(val) {
                return axios
                            .post(`/departments/${val}`).then(response => {
                                return response.data;
                            })
                            .catch(error => {
                                console.log(error)
                            });
            },

            getCities(val) {
                return axios
                            .post(`/cities/${val}`).then(response => {
                                return response.data;
                            })
                            .catch(error => {
                                console.log(error)
                            });
            },
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    type: this.type,
                    number: '',
                    name: null,
                    trade_name: null,
                    country_id: null,
                    department_id: null,
                    // province_id: null,
                    // district_id: null,
                    address: null,
                    telephone: null,
                    // condition: null,
                    // state: null,
                    email: null,
                    perception_agent: false,
                    percentage_perception:0,
                    person_type_id:null,
                    comment:null,
                    type_person_id: null,
                    type_regime_id: null,
                    identity_document_type_id: null,
                    type_obligation_id: null,
                    addresses: [],
                    city_id: null,
                    code: null,
                    dv: null,
                    contact_phone: null,
                    contact_name: null,
                    postal_code: null,
                }

                this.departmentss();
                this.citiess();

            },
            departmentss(edit = false) {
                if (!edit) {
                    // console.log("s")
                    this.form.department_id = null;
                    this.form.city_id = null;
                    this.departments = [];
                    this.cities = [];
                }

                if (this.form.country_id != null) this.getDepartment(this.form.country_id).then(rows => this.departments = rows);
            },
            citiess(edit = false) {
                if (!edit) {
                    this.form.city_id = null;
                    this.cities = [];
                }

                if (this.form.department_id != null) this.getCities(this.form.department_id).then(rows => this.cities = rows);
            },
            async opened() {

                if(this.external && this.input_person) {
                    if(this.form.number.length === 8 || this.form.number.length === 11){
                        if(this.api_service_token != false){
                            await this.$eventHub.$emit('enableClickSearch')
                        }else{
                            this.searchCustomer()
                        }
                    }
                }

            },
            create() {
                if(this.external) {
                    if(this.document_type_id === '01') {
                        this.form.identity_document_type_id = '6'
                    }
                    if(this.document_type_id === '03') {
                        this.form.identity_document_type_id = '1'
                    }

                    if(this.input_person) {
                        this.form.identity_document_type_id = (this.input_person.identity_document_type_id) ? this.input_person.identity_document_type_id: this.form.identity_document_type_id
                        this.form.number = (this.input_person.number) ? this.input_person.number:''
                    }
                }
                if(this.type === 'customers') {
                    this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
                }
                if(this.type === 'suppliers') {
                    this.titleDialog = (this.recordId)? 'Editar Proveedor':'Nuevo Proveedor'
                }
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                            this.departmentss(true);
                            this.citiess(true);
                        })
                }
            },
            clickAddAddress() {
                this.form.addresses.push({
                    'id': null,
                    'country_id': 'PE',
                    'location_id': [],
                    'address': null,
                    'email': null,
                    'phone': null,
                    'main': false,
                });
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if (this.external) {
                                this.$eventHub.$emit('reloadDataPersons', response.data.id)
                            } else {
                                this.$eventHub.$emit('reloadData')
                            }
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            changeIdentityDocType(){
                (this.recordId == null) ? this.setDataDefaultCustomer() : null
            },
            setDataDefaultCustomer(){

                if(this.form.identity_document_type_id == '0'){
                    this.form.number = '99999999'
                    this.form.name = "Clientes - Varios"
                }else{
                    this.form.number = ''
                    this.form.name = null
                }

            },
            close() {
                this.$eventHub.$emit('initInputPerson')
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            searchCustomer() {
                this.searchServiceNumberByType()
            },
            searchNumber(data) {
                this.form.name = (this.form.identity_document_type_id === '1')?data.nombre_completo:data.nombre_o_razon_social;
                this.form.trade_name = (this.form.identity_document_type_id === '6')?data.nombre_o_razon_social:'';
                this.form.location_id = data.ubigeo;
                this.form.address = data.direccion;
                this.form.department_id = (data.ubigeo) ? data.ubigeo[0]:null;
                this.form.province_id = (data.ubigeo) ? data.ubigeo[1]:null;
                this.form.district_id = (data.ubigeo) ? data.ubigeo[2]:null;
                this.form.condition = data.condicion;
                this.form.state = data.estado;

                this.filterProvinces()
                this.filterDistricts()
//                this.form.addresses[0].telephone = data.telefono;
           },
           clickRemoveAddress(index)
           {
                this.form.addresses.splice(index, 1);
           }
        }
    }
</script>
