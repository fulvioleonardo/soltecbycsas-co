<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuracion de la Empresa</h3>
        </div>
        <div class="card-body">
            <div class="general-data">
                <form autocomplete="off">
                    <div class="form-body">
                        <h3 class="my-0">Datos Generales</h3>
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

        <div class="form">
            <div class="software">
                <form autocomplete="off">
                    <div class="form-body">
                        <h3 class="my-0">Software</h3>
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group" :class="{'has-danger': errors.id_software}">
                                    <label class="control-label">Id Software *</label>
                                    <el-input
                                        v-model="software.id_software"
                                        placeholder="Introduzca el Id Software asignado por la DIAN."
                                        :disabled="false"
                                        autofocus>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.id_software" v-text="errors.id_software[0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Pin Software *</label>
                                    <el-input
                                        v-model="software.pin_software"
                                        placeholder="Digite el pin del Software"
                                        :disabled="false"
                                        maxlength="5"
                                        show-word-limit>
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Test Set ID *</label>
                                    <el-input
                                        v-model="software.test_set_id"
                                        placeholder="Introduzca el codigo del Set de Pruebas para habilitacion."
                                        :disabled="false">
                                    </el-input>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right mt-4">
                            <el-button
                                class="submit"
                                type="primary"
                                :loading="loadingSoftware"
                                @click="validateSoftware('software')"
                                native-type="submit">Guardar</el-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="certificado">
                <form autocomplete="off">
                    <div class="form-body">
                        <h3 class="my-0">Certificado</h3>
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <el-upload
                                        ref="fileCertificado"
                                        :auto-upload="false"
                                        :on-change="handleChangeFileCertificado"
                                        :limit="1"
                                        drag
                                        action="''"
                                    >
                                        <i class="el-icon-upload"></i>
                                        <div class="el-upload__text">
                                            Suelta tu archivo aquí o
                                            <em>haz clic para cargar</em>
                                        </div>
                                        <div
                                            slot="tip"
                                            class="el-upload__tip"
                                        >Solo archivos jpg/png con un tamaño menor de 500kb</div>
                                    </el-upload>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Password Certificado *</label>
                                    <el-input
                                        v-model="form.password_certificado"
                                        placeholder="Introduzca el password del certificado digital."
                                        :disabled="false">
                                    </el-input>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right mt-4">
                            <el-button class="submit" type="primary" native-type="submit">Guardar</el-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="resolution">
                <form autocomplete="off">
                    <div class="form-body">
                        <h3 class="my-0">Resolucion de Facturacion</h3>
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Documento *</label>
                                    <el-select v-model="form.type_document_id" filterable remote class="border-left rounded-left border-info" popper-class="el-select-type-document"
                                        dusk="type_document_id"
                                        placeholder="Seleccione el tipo de documento."
                                        :remote-method="searchRemoteCustomers"
                                        @keyup.enter.native="keyupCustomer"
                                        :loading="loading_search"
                                        @change="changeCustomer">

                                        <el-option v-for="option in typeDocuments" :key="option.id" :value="option.id" :label="option.name"></el-option>

                                    </el-select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Prefijo *</label>
                                    <el-input
                                        v-model="form.prefix"
                                        placeholder="Digite el prefijo de la resolucion"
                                        :disabled="false">
                                    </el-input>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Nro Resolucion *</label>
                                    <el-input
                                        v-model="form.nro_resolution"
                                        placeholder="Digite el numero de resolucion."
                                        :disabled="false">
                                    </el-input>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Fecha Resolucion</label>
                                    <el-date-picker v-model="form.date_resolution" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue" :picker-options="datEmision"></el-date-picker>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Fecha Desde</label>
                                    <el-date-picker v-model="form.date_from" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue" :picker-options="datDesde"></el-date-picker>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Fecha Hasta</label>
                                    <el-date-picker v-model="form.date_to" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue" :picker-options="datHasta"></el-date-picker>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right mt-4">
                            <el-button class="submit" type="primary" native-type="submit">Guardar</el-button>
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
            typeDocuments: [
                { id: 1, name: "Factura de Venta Nacional" },
                { id: 2, name: "Factura de Exportación" },
                { id: 3, name: "Factura de Contingencia" },
                { id: 4, name: "Nota Crédito" },
                { id: 5, name: "Nota Débito" },
                { id: 6, name: "ZIP" }
            ],
            errors: {
            },
            fileCertificado: "",
            typeIdentityDocuments: [
            ],
            loadingSoftware: false,
            loadingOther: false,
            loading_form: true,
            typeObligations: [
            ],
            type_documents: {
            },
            versionUbls: [
            ],
            typeRegimes: [
            ],
            departments: [
            ],
            currencies: [
            ],
            countries: [
            ],
            ambients: [
            ],
            cities: [
            ]
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
/*            pickFile() {
                this.$refs.certificate.click();
            },

            onFilePicked(e) {
                let files = e.target.files;

                if (files[0] !== undefined) {
                    this.$set(this.company, "certificate_name", files[0].name);
                    return;
                }
                this.$set(this.company, "certificate_name", "");
            },

            departmentss(mounted = false) {
                if (!mounted) {
                    this.company.department_id = null;
                    this.company.city_id = null;
                    this.departments = [];
                }
                if (this.company.country_id != null)
                    this.getDepartment(this.company.country_id).then(
                        rows => (this.departments = rows)
                    );
            },

            citiess(mounted = false) {
                if (!mounted) this.company.city_id = null;
                    this.cities = [];
                if (this.company.department_id != null)
                    this.getCities(this.company.department_id).then(
                        rows => (this.cities = rows)
                    );
            },

            url(scope = "company", model = null, models = null, modelObject = null) {
                let Data = new FormData(this.company);

                if (this.$refs.certificate.files.length > 0)
                    Data.append(
                        "certificate",
                        this.$refs.certificate.files[0],
                        this.$refs.certificate.files[0].name
                    );
                for (var row in this.company) Data.append(row, this.company[row]);
                    Data.append("_method", "PUT");
                return {
                    url:
                        scope == "company"
                        ? `${this.route}/${scope}/${this.company.id}`
                        : `${this.route}/${model}/${modelObject.id}`,
                    data:
                        scope == "company"
                        ? Data
                        : models.find(model => model.id == modelObject.id)
                };
            },

            validate(scope, model = null, models = null, modelObject = null) {
                this.company.certificate64 = document.getElementById("base64").value;
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) {
                        this.loadingCompany = true;
                        axios
                            .post(`${this.route}/storeServiceCompanie`, this.company)
                            .then(response => {
                                this.$setLaravelMessage(response.data);
                            })
                            .catch(error => {
                                this.$setLaravelValidationErrorsFromResponse(error.response.data);
                                this.$setLaravelErrors(error.response.data);
                            })
                           .then(() => {
                                this.loadingCompany = false;
                            });
                    }
                });
            },  */

            validateSoftware(scope, model = null, models = null, modelObject = null) {
                this.$http.post(`/client/configuration/storeServiceCompanieSoftware`, this.software)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            alert(JSON.stringify(errors.response.data.errors));
                            this.errors = error.response.data.errors
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loadingSoftware = false
                    })
            },

/*            validateCertificate(scope, model = null, models = null, modelObject = null) {
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) {
                        this.certificate.certificate64 = document.getElementById("base64").value;
                        this.loadingCompany = true;
                        axios
                            .post(
                                `${this.route}/storeServiceCompanieCertificate`,
                                this.certificate
                            )
                            .then(response => {
                                this.$setLaravelMessage(response.data);
                            })
                            .catch(error => {
                                this.$setLaravelValidationErrorsFromResponse(error.response.data);
                                this.$setLaravelErrors(error.response.data);
                            })
                            .then(() => {
                                this.loadingCompany = false;
                            });
                    }
                });
            },

            validateResolution(scope, model = null, models = null, modelObject = null) {
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) {
                        this.loadingCompany = true;
                        axios
                            .post(
                            `${this.route}/storeServiceCompanieResolution`,
                            this.resolution
                            )
                            .then(response => {
                                this.$setLaravelMessage(response.data);
                            })
                            .catch(error => {
                                this.$setLaravelValidationErrorsFromResponse(error.response.data);
                                this.$setLaravelErrors(error.response.data);
                            })
                            .then(() => {
                                this.loadingCompany = false;
                            });
                    }
                });
            },

            saveData() {

            },

            handleChangeFileCertificado(file) {
                handleFileSelect(file.raw);
            },

            contructSendObject() {
                return {};
            }   */
        }
    };
</script>
