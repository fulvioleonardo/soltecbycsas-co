<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuracion de la Empresa</h3>
        </div>
        <div class="card-body">
            <div class="general-data">
                <form autocomplete="off">
                    <div class="form-body">
                        <div class="row mt-4">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Numero de Identificacion</label>
                                    <el-input
                                        v-model="form.identification_number"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="control-label">DV</label>
                                    <el-input
                                        v-model="form.dv"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Registro Mercantil</label>
                                    <el-input
                                        v-model="form.merchant_registration"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Direccion</label>
                                    <el-input
                                        v-model="form.address"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Telefono</label>
                                    <el-input
                                        v-model="form.phone"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Lenguaje</label>
                                    <el-input
                                        v-model="form.language.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Impuesto</label>
                                    <el-input
                                        v-model="form.tax.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Pais</label>
                                    <el-input
                                        v-model="form.country.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Documento</label>
                                    <el-input
                                        v-model="form.type_document_identification.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Operacion</label>
                                    <el-input
                                        v-model="form.type_operation.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo Entorno</label>
                                    <el-input
                                        v-model="form.type_environment.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo Moneda</label>
                                    <el-input
                                        v-model="form.type_currency.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Organizacion</label>
                                    <el-input
                                        v-model="form.type_organization.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Municipio</label>
                                    <el-input
                                        v-model="form.municipality.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo Responsabilidad</label>
                                    <el-input
                                        v-model="form.type_liability.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Regimen</label>
                                    <el-input
                                        v-model="form.type_regime.name"
                                        :disabled="true">
                                    </el-input>
                                </div>
                            </div>
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
            }
        },

        data: () => ({
            loading_form: true,
            form: {
                language: {
                },
                tax: {
                },
                country: {
                },
                type_document_identification: {
                },
                type_operation: {
                },
                type_environment: {
                },
                type_currency: {
                },
                type_organization: {
                },
                municipality: {
                },
                type_liability: {
                },
                type_regime: {
                }
            }
        }),

        mounted() {
            this.errors = {
            }
            if (window.File && window.FileReader && window.FileList && window.Blob)
                console.log("ok.");
            else
                alert("The File APIs are not fully supported in this browser.");
        },

        methods: {
        }
    };
</script>
