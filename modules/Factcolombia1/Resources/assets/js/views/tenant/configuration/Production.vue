<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Cambiar Ambiente de Operacion - (HABILITACION - PRODUCCION)</h3>
        </div>
        <div class="card-body">
            <div class="invoice">
                <form>
                    <div class="form-body">
                        <div class="row mt-4 mb-4">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="control-label">ResponseDian <small>(Seleccione de aqui la llave tecnica.)</small></label>
                                    <el-input
                                        type="textarea"
                                        class="custom-textarea"
                                        v-model="production.technicalkey">
                                    </el-input>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right mt-4">
                            <div>
                                <h4 class="d-inline mr-3 font-weight-bold">Facturación: </h4>
                                <el-button :loading="loadingCompany" class="submit" type="primary" @click="validateProduction('H')" >Pasar a Habilitación</el-button>
                                <el-button :loading="loadingCompany" class="submit" type="primary" @click="validateProduction('P')" >Pasar a Producción</el-button>
                            </div>
                            <div class="mt-4">
                                <h4 class="d-inline mr-3 font-weight-bold">Nómina: </h4>
                                <el-button :loading="loadingPayroll" class="submit" type="primary" @click="validateProduction('payrollH')" >Pasar a Habilitación</el-button>
                                <el-button :loading="loadingPayroll" class="submit" type="primary" @click="validateProduction('payrollP')" >Pasar a Producción</el-button>
                            </div>
                            <div class="mt-4">
                                <h4 class="d-inline mr-3 font-weight-bold">Documentos Equivalentes: </h4>
                                <el-button :loading="loadingEqDocs" class="submit" type="primary" @click="validateProduction('eqdocsH')" >Pasar a Habilitación</el-button>
                                <el-button :loading="loadingEqDocs" class="submit" type="primary" @click="validateProduction('eqdocsP')" >Pasar a Producción</el-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
    .custom-textarea .el-textarea__inner textarea {
        height: 350px;
    }
</style>

<script>
    import Helper from "../../../mixins/Helper";

    export default {
        mixins: [Helper],
        data: () => ({
            loadingCompany: false,
            production: { technicalkey: ''},
            route: 'co-configuration/production',
            loadingPayroll: false,
            loadingEqDocs: false
        }),

        methods: {
            validateProduction(environment) {
                if(['P', 'H'].includes(environment))
                    this.loadingCompany = true;
                else
                    if(['payrollP', 'payrollH'].includes(environment))
                        this.loadingPayroll = true;
                    else
                        this.loadingEqDocs = true;

                axios
                    .post(`${this.route}/changeEnvironmentProduction/${environment}`)
                    .then(response => {
                        this.$message.success(response.data)
                    })
                    .catch(error => {
                        this.$message.error(error.response.data)
                    })
                    .then(() => {
                        this.loadingCompany = false;
                        this.loadingPayroll = false;
                        this.loadingEqDocs = false;
                    });

                if(environment == 'P'){
                    this.loadingCompany = true;
                    axios
                        .post(`${this.route}/queryTechnicalKey`)
                        .then(response => {
    //                        this.$setLaravelMessage(response.data);
                            if(response.data.success){
                                this.production.technicalkey = JSON.stringify(response.data, null, 2)
                            }
                            else{
                                this.$message.error(response.data.message)
                            }
                        })
                        .catch(error => {
                        // this.$setLaravelValidationErrorsFromResponse(error.response.data);
                            //this.$setLaravelErrors(error.response.data);
                            this.$message.error(error.response.data)
                        })
                        .then(() => {
                            this.loadingCompany = false;
                        });
                }
                else
                    this.production.technicalkey = "No se pueden consultar claves tecnicas para ambiente de HABILITACION."
            }
        }
    };
</script>
