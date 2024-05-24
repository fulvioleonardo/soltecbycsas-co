<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción</label>
                            <el-input v-model="form.description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.code}">
                            <label class="control-label">Código Domicilio Fiscal</label>
                            <el-input v-model="form.code" :maxlength="4"></el-input>
                            <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
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
                    <!-- <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.province_id}">
                            <label class="control-label">Distrito</label>
                            <el-select v-model="form.district_id" filterable>
                                <el-option v-for="option in districts" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.district_id" v-text="errors.district_id[0]"></small>
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.address}">
                            <label class="control-label">Dirección Fiscal</label>
                            <el-input v-model="form.address"></el-input>
                            <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.telephone}">
                            <label class="control-label">Teléfono</label>
                            <el-input v-model="form.telephone"></el-input>
                            <small class="form-control-feedback" v-if="errors.telephone" v-text="errors.telephone[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.trade_address}">
                            <label class="control-label">Dirección Comercial</label>
                            <el-input v-model="form.trade_address"></el-input>
                            <small class="form-control-feedback" v-if="errors.trade_address" v-text="errors.trade_address[0]"></small>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo de contacto</label>
                            <el-input v-model="form.email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.web_address}">
                            <label class="control-label">Dirección web</label>
                            <el-input v-model="form.web_address"></el-input>
                            <small class="form-control-feedback" v-if="errors.web_address" v-text="errors.web_address[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.aditional_information}">
                            <label class="control-label">Información adicional</label>
                            <el-input v-model="form.aditional_information"></el-input>
                            <small class="form-control-feedback" v-if="errors.aditional_information" v-text="errors.aditional_information[0]"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Logo JPG Establecimiento</label>
                            <el-input v-model="form.establishment_logo" :readonly="true">
                                <el-upload slot="append"
                                    :headers="headers"
                                    :data="{'type': 'establishment_logo', 'establishment_id': form.id}"
                                    action="/companies/uploads"
                                    :show-file-list="false"
                                    :on-success="successUpload">
                                    <el-button type="primary" icon="el-icon-upload"></el-button>
                                </el-upload>
                            </el-input>
                            <div class="sub-title text-danger"><small>Debe ser un archivo JPG</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>

</template>

<script>
    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                headers: headers_token,
                resource: 'establishments',
                errors: {},
                form: {},
                countries: [],
                departments: [],
                cities: [],
                all_provinces: [],
                all_districts: [],
                provinces: [],
                districts: [],
            }
        },

        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.countries = response.data.countries
                    // this.all_departments = response.data.departments
                    // this.all_provinces = response.data.provinces
                    // this.all_districts = response.data.districts
                })
        },
        methods: {
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
                    description: null,
                    country_id: null,
                    department_id: null,
                    city_id: null,
                    address: null,
                    telephone: null,
                    email: null,
                    code: null,
                    trade_address: null,
                    web_address: null,
                    aditional_information: null,
                    establishment_logo: null,
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
            async create() {
                this.titleDialog = (this.recordId)? 'Editar Establecimiento':'Nuevo Establecimiento'
                if (this.recordId) {
                    await this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            if (response.data !== '') {
                                this.form = response.data.data
                                // this.filterProvinces()
                                // this.filterDistricts()

                                this.departmentss(true);
                                this.citiess(true);
                            }
                        })
                }
//                console.log(this.form)
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
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
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            filterProvince() {
                this.form.province_id = null
                this.form.district_id = null
                this.filterProvinces()
            },
            filterProvinces() {
                this.provinces = this.all_provinces.filter(f => {
                    return f.department_id === this.form.department_id
                })
            },
            filterDistrict() {
                this.form.district_id = null
                this.filterDistricts()
            },
            filterDistricts() {
                this.districts = this.all_districts.filter(f => {
                    return f.province_id === this.form.province_id
                })
            },

            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },

            successUpload(response, file, fileList) {
//                console.log(response)
                if (response.success) {
                    this.$message.success(response.message)
                    this.form[response.type] = response.name
                } else {
                    // this.$message({message:'Error al subir el archivo', type: 'error'})
                    this.$message({message: response.message, type: 'error'})
                }
            },

        }
    }
</script>
