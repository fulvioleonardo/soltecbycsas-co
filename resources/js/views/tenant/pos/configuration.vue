<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuración POS</h3>
        </div>
        <div class="card-body">
            <div>
                <el-table
                :data="records"
                style="width: 100%">
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
                        prop="date_from"
                        label="Fecha Desde">
                    </el-table-column>
                    <el-table-column
                        prop="date_end"
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
                        prop="electronic"
                        label="POS Electronico">
                        <template slot-scope="scope">
                            <el-checkbox
                                v-model="scope.row.electronic"
                                :disabled="true"
                            ></el-checkbox>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="plate_number"
                        label="Serial Caja">
                    </el-table-column>
                    <el-table-column
                        prop="cash_type"
                        label="Tipo Caja">
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        label="Operaciones"
                        width="120">
                        <template slot-scope="scope">
                            <el-button
                            icon="el-icon-check"
                            @click.native.prevent="selection(scope.row)"
                            size="mini">
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <div class="resolution">
                <form autocomplete="off">
                    <div class="form-body">
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Documento</label>
                                    <el-input
                                        :value="'POS'"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.prefix}">
                                    <label class="control-label">Prefijo *</label>
                                    <el-input
                                        v-model="resolution.prefix"
                                        placeholder="Digite el prefijo de la resolucion"
                                        maxlength="4"
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.prefix" v-text="errors.prefix[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.resolution_number}">
                                    <label class="control-label">Nro Resolucion *</label>
                                    <el-input
                                        v-model="resolution.resolution_number"
                                        placeholder="Digite el numero de resolucion."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.resolution_number" v-text="errors.resolution_number[0]"></small>
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
                                <div class="form-group" :class="{'has-danger': errors.date_end}">
                                    <label class="control-label">Fecha Hasta</label>
                                    <el-date-picker
                                        v-model="resolution.date_end"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        placeholder="Seleccione la fecha final de validez de la resolucion."
                                        :clearable="false">
                                    </el-date-picker>
                                    <small class="form-control-feedback" v-if="errors.date_end" v-text="errors.date_end[0]"></small>
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
                                <div class="form-group" :class="{'has-danger': errors.electronic}">
                                    <label class="control-label">POS Electronico</label><br>
                                    <el-checkbox  v-model="resolution.electronic"></el-checkbox>
                                    <small class="form-control-feedback" v-if="errors.electronic" v-text="errors.electronic[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4" v-if="resolution.electronic">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.plate_number}">
                                    <label class="control-label">Serial Caja</label>
                                    <el-input
                                        v-model="resolution.plate_number"
                                        placeholder="Introduzca el numero serial de la caja."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.plate_number" v-text="errors.plate_number[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.cash_type}">
                                    <label class="control-label">Tipo Caja</label>
                                    <el-input
                                        v-model="resolution.cash_type"
                                        placeholder="Digite el tipo de caja."
                                        :disabled="false">
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.cash_type" v-text="errors.cash_type[0]"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions text-right mt-4">
                            <el-button type="default " @click="clearFields()">Limpiar campos</el-button>
                            <el-button
                                type="primary"
                                :loading="loadingResolution"
                                @click="validateResolution()">Guardar
                            </el-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
   // import Helper from "../../../mixins/Helper";
    export default {
       // mixins: [Helper],
        props: ['configuration'],
        data: () => ({
            typeDocuments: [
                { id: 1, name: "Factura de Venta Nacional" },
                //{ id: 2, name: "Factura de Exportación" },
                //{ id: 3, name: "Factura de Contingencia" },
                { id: 4, name: "Nota Crédito" },
                { id: 5, name: "Nota Débito" },
                { id: 6, name: "ZIP" }
            ],

            errors: {
            },

            resolution: {
            },

            loadingResolution: false,
            records: []
        }),

        mounted() {
            this.errors = {
            }
            if (window.File && window.FileReader && window.FileList && window.Blob)
                console.log("ok.");
            else
                alert("The File APIs are not fully supported in this browser.");


            /*if(this.configuration)
            {
                this.resolution.prefix = this.configuration.prefix;
                this.resolution.resolution_number = this.configuration.resolution_number;
                this.resolution.resolution_date = this.configuration.resolution_date;
                this.resolution.date_from = this.configuration.date_from;
                this.resolution.date_end = this.configuration.date_end;
                this.resolution.from = this.configuration.from;
                this.resolution.to = this.configuration.to;
            }*/

            this.getRecords()
        },

        methods: {
            getRecords()
            {
                this.$http.get(`/pos/records`, this.resolution)
                    .then(response => {
                        this.records = response.data.data
                        console.log(this.records)
                    })
                    .catch(error => {

                    })
                    .then(() => {
                    })
            },

            initForm() {
                this.resolution = {
                    prefix : '',
                    resolution_number: '',
                    resolution_date: '',
                    date_from: '',
                    date_end: '',
                    from: '',
                    to: '',
                    electronic: true,
                    plate_number: '',
                    cash_type: '',
                }
            },

            validateResolution() {
                this.loadingResolution = true
                this.$http.post(`/pos/configuration`, this.resolution)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if(this.resolution.electronic)
                                localStorage.setItem("plate_number", this.resolution.plate_number);
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
                        //this.initForm()
                    })
            },

            selection(row) {
                this.resolution = {
                    prefix : row.prefix,
                    resolution_number: row.resolution_number,
                    resolution_date: row.resolution_date,
                    date_from: row.date_from,
                    date_end: row.date_end,
                    from: row.from,
                    to: row.to,
                    electronic: row.electronic,
                    plate_number: row.plate_number,
                    cash_type: row.cash_type
                }
            },

            clearFields(){
                this.initForm()
            }
        }
    };
</script>
