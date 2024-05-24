<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Resolucion de Facturacion</h3>
        </div>
        <div class="card-body">
            <div>

                <el-table
                :data="records"
                style="width: 100%">
                    <el-table-column
                        prop="name"
                        label="Tipo Doc."
                        width="180">
                    </el-table-column>
                    <el-table-column
                        prop="prefix"
                        label="Prefijo"
                        width="120">
                    </el-table-column>
                    <el-table-column
                        prop="resolution_number"
                        label="Número">
                    </el-table-column>
                    <el-table-column
                        prop="resolution_date"
                        label="Fecha Desde">
                    </el-table-column>
                    <el-table-column
                        prop="resolution_date_end"
                        label="Fecha Hasta">
                    </el-table-column>
                    <el-table-column
                        prop="from"
                        label="Desde">
                    </el-table-column>
                    <el-table-column
                        prop="to"
                        label="Hasta">
                    </el-table-column>
                    <el-table-column
                        prop="description"
                        label="Descripcion">
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        label="Seleccionar"
                        width="100">
                        <template slot-scope="scope">
                            <el-button
                            icon="el-icon-check"
                            @click.native.prevent="selection(scope.row)"
                            size="mini">
                            </el-button>
<!--                            <el-button
                            type="danger"
                            plain
                            icon="el-icon-close"
                            @click.native.prevent="deleter(scope.row.id)"
                            size="mini">
                            </el-button>        -->
                        </template>
                    </el-table-column>
                </el-table>
            </div>

            <div class="resolution">
                <form autocomplete="off">
                    <div class="form-body">
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.type_document_id}">
                                    <label class="control-label">Tipo de Documento *</label>
                                    <el-select
                                        @change="changeTypeDocument"
                                        v-model="resolution.type_document_id"
                                        filterable
                                        remote class="border-left rounded-left border-info"
                                        popper-class="el-select-type-document"
                                        placeholder="Seleccione el tipo de documento."
                                        :disabled="disableIndexFieldsResolution">
                                        <el-option
                                            v-for="option in typeDocuments"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.name">
                                        </el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.type_document_id" v-text="errors.type_document_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.prefix}">
                                    <label class="control-label">Prefijo *</label>
                                    <el-input
                                        v-model="resolution.prefix"
                                        placeholder="Digite el prefijo de la resolucion"
                                        maxlength="4"
                                        :disabled="disableIndexFieldsResolution">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.prefix" v-text="errors.prefix[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.resolution}">
                                    <label class="control-label">Nro Resolucion *</label>
                                    <el-input
                                        v-model="resolution.resolution"
                                        placeholder="Digite el numero de resolucion."
                                        :disabled="disableIndexFieldsResolution">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.resolution" v-text="errors.resolution[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.resolution_date}">
                                    <label class="control-label">Fecha Resolucion</label>
                                    <el-date-picker
                                        v-model="resolution.resolution_date"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        placeholder="Seleccione la fecha de emision de la resolucion."
                                        :clearable="false">
                                    </el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.resolution_date" v-text="errors.resolution_date[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.date_from}">
                                    <label class="control-label">Fecha Desde</label>
                                    <el-date-picker
                                        v-model="resolution.date_from"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        placeholder="Seleccione la fecha inicial de validez de la resolucion."
                                        :clearable="false">
                                    </el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_from" v-text="errors.date_from[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.date_to}">
                                    <label class="control-label">Fecha Hasta</label>
                                    <el-date-picker
                                        v-model="resolution.date_to"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        placeholder="Seleccione la fecha final de validez de la resolucion."
                                        :clearable="false">
                                    </el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_to" v-text="errors.date_to[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.from}">
                                    <label class="control-label">Desde *</label>
                                    <el-input
                                        v-model="resolution.from"
                                        placeholder="Introduzca el numero inicial de la resolucion."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.from" v-text="errors.from[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.to}">
                                    <label class="control-label">Hasta *</label>
                                    <el-input
                                        v-model="resolution.to"
                                        placeholder="Digite el numero final de la resolucion."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.to" v-text="errors.to[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.technical_key}">
                                    <label class="control-label">Clave Tecnica *</label>
                                    <el-input
                                        v-model="resolution.technical_key"
                                        placeholder="Introduzca la clave tecnica para esta resolucion."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.technical_key" v-text="errors.technical_key[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.description}">
                                    <label class="control-label">Descripcion</label>
                                    <el-input
                                        v-model="resolution.description"
                                        placeholder="Cadena descriptiva de la resolucion."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions text-right mt-4">
                            <el-button type="default " @click="clearFields()">Limpiar campos</el-button>
                            <el-button type="primary" :loading="loadingResolution" @click="validateResolution()">Guardar</el-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Helper from "../../../mixins/Helper";
    export default {
        mixins: [Helper],
        props: {
            route: {
                required: true
            },
        },
        data: () => ({
            typeDocuments: [
                { id: 1, name: "Factura de Venta Nacional", code: '1' },
                //{ id: 2, name: "Factura de Exportación", code: '2' },
                //{ id: 3, name: "Factura de Contingencia", code: '3' },
                { id: 4, name: "Nota Crédito", code: '4' },
                { id: 5, name: "Nota Débito", code: '5' },
                { id: 6, name: "ZIP", code: '6'},
                { id: 9, name: "Nomina Individual", code: '9'},
                { id: 10, name: "Nomina Individual de Ajuste", code: '10'},
                { id: 11, name: "Documento Soporte Electrónico", code: '11'},
                { id: 13, name: "Nota de Ajuste al Documento Soporte Electrónico", code: '13'},
            ],
            errors: {
            },
            resolution: {
            },
            loadingResolution: false,
            disableIndexFieldsResolution: false,
            records: []
        }),

        async created(){
            await this.getRecords()
            await this.initForm()
        },

        mounted() {
            this.errors = {
            }
            if (window.File && window.FileReader && window.FileList && window.Blob)
                console.log("ok.");
            else
                alert("The File APIs are not fully supported in this browser.");
        },

        methods: {
            changeTypeDocument()
            {
                if(this.resolution.type_document_id)
                {
                    const type = this.typeDocuments.find(x=> x.id == this.resolution.type_document_id)
                    this.resolution.code = type.code
                    this.resolution.name = type.name
                }
            },

            getRecords()
            {
                this.$http.get(`/client/configuration/co_type_documents`).then(response=>{
                    this.records = response.data.data
                })
            },

            initForm() {
                this.resolution = {
                    type_document_id : null,
                    prefix : null,
                    resolution : null,
                    resolution_date : null,
                    date_from : null,
                    date_to : null,
                    from : null,
                    to : null,
                    technical_key : null,
                    code : null,
                    name : null,
                    description: null
                }
                this.disableIndexFieldsResolution = false
            },

            clearFields(){
                this.disableIndexFieldsResolution = false
                this.initForm()
            },

            validateResolution(scope, model = null, models = null, modelObject = null) {
                this.loadingResolution = true
                this.$http.post(`/client/configuration/storeServiceCompanieResolution`, this.resolution)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.getRecords()
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
                        this.loadingResolution = false
                        this.initForm()
                    })
            },

            selection(row)
            {
                const type_doc = this.typeDocuments.find(x=> x.code == row.code)
                this.resolution = {
                    type_document_id : type_doc.id,
                    prefix : row.prefix,
                    resolution : row.resolution_number,
                    resolution_date : row.resolution_date,
                    date_from : row.resolution_date,
                    date_to : row.resolution_date_end,
                    from : row.from,
                    to : row.to,
                    technical_key : row.technical_key,
                    code : type_doc.code,
                    name : type_doc.name,
                    description: row.description
                }
                this.disableIndexFieldsResolution = true
            },

            deleter(id)
            {
                this.$confirm('¿Desea eliminar el registro?', 'Eliminar', {
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.delete(`/client/configuration/storeServiceCompanieResolution/${id}`)
                        .then(res => {
                            if(res.data.success) {
                                this.$message.success(res.data.message)
                                this.getRecords()
                                resolve()
                            }else{
                                this.$message.error(res.data.message)
                                resolve()
                            }

                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar eliminar');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })

                }).catch(error => {
                    console.log(error)
                });
            }
        }
    };
</script>
