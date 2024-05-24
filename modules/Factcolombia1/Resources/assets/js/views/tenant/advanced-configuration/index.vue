<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="#"><i class="fas fa-cogs"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span></li>
                <li><span class="text-muted">Avanzado</span></li>
            </ol>
        </div>
        <div class="card card-dashboard border">
            <div class="card-body">
                <template>
                    <form autocomplete="off">
                        <el-tabs v-model="activeName">
                            
                            <el-tab-pane class="mb-3"
                                         name="general">
                                <span slot="label">General</span>
                                <div class="row">
                                    
                                    <div class="col-md-4 mt-4" :class="{'has-danger': errors.uvt}">
                                        <div class="form-group">
                                            <label class="control-label">Valor UVT
                                                <el-tooltip class="item" effect="dark" content="Límite UVT = 5 x Valor UVT" placement="top-start">
                                                    <i class="fa fa-info-circle"></i>
                                                </el-tooltip>
                                            </label>
                                            <el-input-number v-model="form.uvt" :min="0" controls-position="right"
                                                             @change="submit"></el-input-number>

                                            <small class="form-control-feedback" v-if="errors.uvt" v-text="errors.uvt[0]"></small>
                                        </div>
                                    </div>

                                </div> 
                            </el-tab-pane> 

                            <el-tab-pane class="mb-3"
                                         name="payroll">
                                <span slot="label">Nómina</span>
                                <div class="row">
                                    
                                    <div class="col-md-4 mt-4">
                                        <label class="control-label">Salario mínimo</label>
                                        <div class="form-group">
                                            <el-input-number v-model="form.minimum_salary" :min="0" controls-position="right"
                                                             @change="submit"></el-input-number>

                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4 mt-4">
                                        <label class="control-label">Subsidio de transporte</label>
                                        <div class="form-group">
                                            <el-input-number v-model="form.transportation_allowance" :min="0" controls-position="right"
                                                             @change="submit"></el-input-number>
                                        </div>
                                    </div>

                                </div> 
                            </el-tab-pane> 

                            
                            <el-tab-pane class="mb-3" name="radian">
                                <span slot="label">Recepción documentos (RADIAN)</span>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Configuración de correo electrónico</h4>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="control-label">Dirección del host</label>
                                        <div class="form-group">
                                            <el-input v-model="form.radian_imap_host"></el-input>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="control-label">Puerto del host</label>
                                        <div class="form-group">
                                            <el-input v-model="form.radian_imap_port"></el-input>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mt-2">
                                        <label class="control-label">Encriptación</label>
                                        <div class="form-group">
                                            <el-input v-model="form.radian_imap_encryption"></el-input>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="control-label">Correo electrónico</label>
                                        <div class="form-group">
                                            <el-input v-model="form.radian_imap_user"></el-input>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mt-2">
                                        <label class="control-label">Contraseña</label>
                                        <div class="form-group">
                                            <el-input v-model="form.radian_imap_password" show-password></el-input>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <div class="form-actions text-right">
                                            <el-button class="submit" type="primary" @click.prevent="clickSaveEmailRadian" :loading="loading_submit">Guardar</el-button>
                                        </div>
                                    </div>

                                </div> 
                            </el-tab-pane> 

                        </el-tabs> 
                    </form>
                </template>
            </div>
        </div>
    </div>
</template>

<script>


export default {
    data() {
        return {
            loading_submit: false,
            resource: 'co-advanced-configuration',
            errors: {},
            form: {},
            loading_submit: false,
            activeName: 'general'
        }
    },
    created() {
        this.getRecord()
    },
    methods: {
        async getRecord() {

            await this.$http.get(`/${this.resource}/record`).then(response => {
                this.form = response.data.data
            })
        },
        initForm() {
            this.errors = {}
            this.form = {
                minimum_salary: 0,
                transportation_allowance: 0,
                radian_imap_encryption: null,
                radian_imap_host: null,
                radian_imap_port: null,
                radian_imap_password: null,
                radian_imap_user: null,
                uvt: 0,
            }
        },
        clickSaveEmailRadian()
        {
            if(!this.form.radian_imap_encryption || !this.form.radian_imap_host || !this.form.radian_imap_port || !this.form.radian_imap_password || !this.form.radian_imap_user)
            {
                return this.$message.error('Todos los campos son obligatorios')
            }

            this.submit()
        },
        submit() {

            this.loading_submit = true

            this.$http.post(`/${this.resource}`, this.form).then(response => {
                let data = response.data
                if (data.success) {
                    this.$message.success(data.message)
                    this.getRecord()
                } else {
                    this.$message.error(data.message)
                }

            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data
                } else {
                    console.log(error)
                }
            }).then(() => {
                this.loading_submit = false
            })
        },
    }
}
</script>
