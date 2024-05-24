<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h4><b>Datos de la empresa</b></h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.identification_number}">
                            <label class="control-label">Número de identificación</label>
                            <el-input  v-model="form.identification_number" :maxlength="15" :disabled="form.is_update">
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.identification_number" v-text="errors.identification_number[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.dv}">
                            <label class="control-label">Dv</label>
                            <el-input  v-model="form.dv" :disabled="form.is_update"></el-input>
                            <small class="form-control-feedback" v-if="errors.dv" v-text="errors.dv[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre de la Empresa</label>
                            <el-input  v-model="form.name" :disabled="form.is_update"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': (errors.subdomain || errors.uuid)}">
                            <label class="control-label">Nombre de Subdominio</label>
                            <el-input v-model="form.subdomain" :disabled="form.is_update">
                                <template slot="append">{{ url_base }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.subdomain" v-text="errors.subdomain[0]"></small>
                            <small class="form-control-feedback" v-if="errors.uuid" v-text="errors.uuid[0]"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="control-label">
                            API Token
                            <el-tooltip class="item" effect="dark" content="Código de acceso que permite a las aplicaciones o usuarios ejecutar funciones de API" placement="top-start">
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </label>
                        <el-input v-model="form.api_token" :disabled="true"></el-input>
                        <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="copyToClipboard">Copiar</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h4><b>Datos usuario</b></h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo de Acceso</label>
                            <el-input  v-model="form.email" :disabled="form.is_update"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': (errors.password)}">
                            <label class="control-label">Contraseña</label>
                            <el-input type="password"  v-model="form.password"></el-input>
                            <small class="form-control-feedback" v-if="errors.password" v-text="errors.password[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': (errors.password_confirmation)}">
                            <label class="control-label">Confirmar contraseña</label>
                            <el-input type="password"  v-model="form.password_confirmation"></el-input>
                            <small class="form-control-feedback" v-if="errors.password_confirmation" v-text="errors.password_confirmation[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div  class="form-group" :class="{'has-danger': errors.type_liability_id}">
                            <label class="control-label">Tipo de responsabilidad</label>
                            <el-select filterable  v-model="form.type_liability_id">
                                <el-option v-for="option in type_liabilities" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_liability_id" v-text="errors.type_liability_id[0]"></small>
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-12 mb-2">
                        <h4><b>Datos generales</b></h4>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': (errors.limit_documents)}">
                            <label class="control-label">Límite de documentos</label>
                            <el-input  v-model="form.limit_documents"></el-input>
                            <small class="form-control-feedback" v-if="errors.limit_documents" v-text="errors.limit_documents[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': (errors.limit_users)}">
                            <label class="control-label">Límite de usuarios</label>
                            <el-input  v-model="form.limit_users"></el-input>
                            <small class="form-control-feedback" v-if="errors.limit_users" v-text="errors.limit_users[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': (errors.economic_activity_code)}">
                            <label class="control-label">Actividad económica</label>
                            <el-input  v-model="form.economic_activity_code"></el-input>
                            <small class="form-control-feedback" v-if="errors.economic_activity_code" v-text="errors.economic_activity_code[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': (errors.ica_rate)}">
                            <label class="control-label">Tasa ICA</label>
                            <el-input  v-model="form.ica_rate"></el-input>
                            <small class="form-control-feedback" v-if="errors.ica_rate" v-text="errors.ica_rate[0]"></small>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div  class="form-group" :class="{'has-danger': errors.type_document_identification_id}">
                            <label class="control-label">Seleccionar Tipo Documento</label>
                            <el-select filterable  v-model="form.type_document_identification_id">
                                <el-option v-for="option in type_document_identifications" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_document_identification_id" v-text="errors.type_document_identification_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div  class="form-group" :class="{'has-danger': errors.department_id}">
                            <label class="control-label">Seleccionar Departamento</label>
                            <el-select filterable  v-model="form.department_id" @change="cascade">
                                <el-option v-for="option in departments" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.department_id" v-text="errors.department_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div  class="form-group" :class="{'has-danger': errors.municipality_id}">
                            <label class="control-label">Seleccionar Municipio</label>
                            <el-select filterable  v-model="form.municipality_id">
                                <el-option v-for="option in municipalities" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.municipality_id" v-text="errors.municipality_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div  class="form-group" :class="{'has-danger': errors.type_organization_id}">
                            <label class="control-label">Seleccionar Tipo Organizacion</label>
                            <el-select filterable  v-model="form.type_organization_id">
                                <el-option v-for="option in type_organizations" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_organization_id" v-text="errors.type_organization_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div  class="form-group" :class="{'has-danger': errors.type_regime_id}">
                            <label class="control-label">Seleccionar Regimen</label>
                            <el-select filterable  v-model="form.type_regime_id">
                                <el-option v-for="option in type_regimes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type_regime_id" v-text="errors.type_regime_id[0]"></small>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': (errors.merchant_registration)}">
                            <label class="control-label">Registro mercantil</label>
                            <el-input  v-model="form.merchant_registration"></el-input>
                            <small class="form-control-feedback" v-if="errors.merchant_registration" v-text="errors.merchant_registration[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': (errors.address)}">
                            <label class="control-label">Dirección</label>
                            <el-input  v-model="form.address"></el-input>
                            <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': (errors.phone)}">
                            <label class="control-label">Teléfono</label>
                            <el-input  v-model="form.phone"></el-input>
                            <small class="form-control-feedback" v-if="errors.phone" v-text="errors.phone[0]"></small>
                        </div>
                    </div>

                </div>
                <div class="row mt-2">
                    <div class="col-md-12" >
                        <div class="form-group">
                            <label class="control-label">Módulos</label>
                            <div class="row">
                                <div class="col-4" v-for="(module,ind) in form.modules" :key="ind">
                                    <el-checkbox v-model="module.checked">{{ module.description }}</el-checkbox>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" dusk="submit">
                    <template v-if="loading_submit">
                        {{button_text}}
                    </template>
                    <template v-else>
                        Guardar
                    </template>
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>


<script>

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                headers: headers_token,
                loading_submit: false,
                loading_search: false,
                titleDialog: null,
                button_text:null,
                resource: 'co-companies',
                error: {},
                errors: {},
                form: {},
                url_base: null,
                departments:[],
                municipalities:[],
                all_municipalities:[],
                type_document_identifications: [],
                type_organizations: [],
                modules: [],
                type_regimes: [],
                toggle: false,
                type_liabilities: [],
            }
        },
        async created() {
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.modules = response.data.modules
                    this.departments = response.data.departments
                    this.all_municipalities = response.data.municipalities
                    this.type_document_identifications = response.data.type_document_identifications
                    this.type_organizations = response.data.type_organizations
                    this.type_regimes = response.data.type_regimes
                    this.url_base = response.data.url_base
                    this.type_liabilities = response.data.type_liabilities
                })

            await this.initForm()
        },

        methods: {
            copyToClipboard() {
                const el = document.createElement('textarea');
                el.value = this.form.api_token;
                el.setAttribute('readonly', '');
                el.style.position = 'absolute';
                el.style.left = '-9999px';
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                console.log('Texto copiado al portapapeles:', this.form.api_token);
            },

            cascade() {
                this.form.municipality_id = null
                this.municipalities = this.all_municipalities.filter(
                    x => x.department_id == this.form.department_id
                )
            },

            filterMunicipalities() {
                this.municipalities = this.all_municipalities.filter(
                    x => x.department_id == this.form.department_id
                )
            },
            initForm() {

                this.errors = {}
                this.form = {
                    id: null,
                    is_update: false,
                    identification_number: null,
                    name: null,
                    subdomain: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    api_token: null,
                    department_id: null,
                    municipality_id: null,
                    type_organization_id: null,
                    type_regime_id: null,
                    merchant_registration: null,
                    address: null,
                    phone: null,
                    economic_activity_code: null,
                    ica_rate: null,
                    limit_documents: null,
                    limit_users: 1,
                    type_document_identification_id: null,
                    dv: 1,
                    language_id: 79,
                    country_id: 46,
                    type_liability_id: 14,
                    id_service: null,
                    modules: []
                }

                this.modules.forEach(module => {
                    this.form.modules.push({
                        id: module.id,
                        description: module.description,
                        checked: true
                    })
                })
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar compañia':'Nuevo compañia'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                                this.form = response.data.data
                                this.form.is_update = true
                                this.filterMunicipalities()
                            })
                }
            },
            hasModules(){

                let modules_checked = 0
                this.form.modules.forEach(module =>{
                    if(module.checked){
                        modules_checked++
                    }
                })

                return (modules_checked > 0) ? true:false

            },
            async submit() {

                // console.log(this.form)

                if(!this.form.is_update){
                    let has_modules = await this.hasModules()
                    if(!has_modules)
                        return this.$message.error('Debe seleccionar al menos un módulo')
                }


                this.button_text = (this.form.is_update) ? 'Actualizando compañia...':'Creando base de datos...'
                this.loading_submit = true
                await this.$http.post(`${this.resource}${(this.form.is_update ? '/update' : '')}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        }else if(error.response.status === 500){
                            this.$message.error(error.response.data.message);
                        }
                         else {
                            console.log(error.response)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            errorUpload(r)
            {
                console.log(r)
            },
            successUpload(response)
            {
                if (response.success) {
                    this.form.certificate = response.data.filename
                   // this.form.image_url = response.data.temp_image
                    this.form.temp_path = response.data.temp_path
                } else {
                    this.$message.error(response.message)
                }
            }
        }
    }
</script>
