<template>
    <el-dialog :title="title" :visible="showDialog" @close="close" @open="getData" width="65%">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12" v-if="records.length > 0">

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha de pago</th>
                                <th>Método de pago</th>
                                <th>Destino</th>
                                <th>Referencia</th>
                                <th class="text-right">Monto</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in records" :key="index">
                                <template v-if="row.id">
                                    <td>PAGO-{{ row.id }}</td>
                                    <td>{{ row.date_of_payment }}</td>
                                    <td>{{ row.payment_method_type_description }}</td>
                                    <td>{{ row.destination_description }}</td>
                                    <td>{{ row.reference }}</td>
                                    <td class="text-right">{{ row.payment }}</td>
                                    <td class="series-table-actions text-right">
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                                    </td>
                                </template>
                                <template v-else>
                                    <td></td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.date_of_payment}">
                                            <el-date-picker v-model="row.date_of_payment"
                                                            type="date"
                                                            :clearable="false"
                                                            format="dd/MM/yyyy"
                                                            value-format="yyyy-MM-dd"></el-date-picker>
                                            <small class="form-control-feedback" v-if="row.errors.date_of_payment" v-text="row.errors.date_of_payment[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.payment_method_type_id}">
                                            <el-select v-model="row.payment_method_type_id">
                                                <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                            </el-select>
                                            <small class="form-control-feedback" v-if="row.errors.payment_method_type_id" v-text="row.errors.payment_method_type_id[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.payment_destination_id}">
                                            <el-select v-model="row.payment_destination_id" filterable :disabled="row.payment_destination_disabled">
                                                <el-option v-for="option in payment_destinations" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                            </el-select>
                                            <small class="form-control-feedback" v-if="row.errors.payment_destination_id" v-text="row.errors.payment_destination_id[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.reference}">
                                            <el-input v-model="row.reference"></el-input>
                                            <small class="form-control-feedback" v-if="row.errors.reference" v-text="row.errors.reference[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.payment}">
                                            <el-input v-model="row.payment"></el-input>
                                            <small class="form-control-feedback" v-if="row.errors.payment" v-text="row.errors.payment[0]"></small>
                                        </div>
                                    </td>
                                    <td class="series-table-actions text-right">
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickSubmit(index)">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancel(index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </template>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">TOTAL PAGADO</td>
                                <td class="text-right">{{ remission.total_paid }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">TOTAL A PAGAR</td>
                                <td class="text-right">{{ remission.total }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">PENDIENTE DE PAGO</td>
                                <td class="text-right">{{ remission.total_difference }}</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 text-center pt-2" v-if="showAddButton && (remission.total_difference > 0)">
                    <el-button type="primary" icon="el-icon-plus" @click="clickAddRow">Nuevo</el-button>
                </div>
            </div>
        </div>
    </el-dialog>

</template>

<script>

    import {deletable} from '@mixins/deletable'

    export default {
        props: ['showDialog', 'remissionId'],
        mixins: [deletable],
        data() {
            return {
                title: null,
                resource: 'co-remission-payments',
                records: [],
                payment_destinations:  [],
                payment_method_types: [],
                showAddButton: true,
                remission: {},
            }
        },
        async created() {
            await this.initForm()

            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.payment_method_types = response.data.payment_method_types
                    this.payment_destinations = response.data.payment_destinations
                })
        },
        methods: {
            initForm() {
                this.records = []
                this.showAddButton = true
            },
            async getData() {

                this.initForm()

                await this.$http.get(`/${this.resource}/remission/${this.remissionId}`)
                    .then(response => {
                        this.remission = response.data
                        this.title = 'Pagos de la remisión: '+this.remission.number_full
                    })

                await this.$http.get(`/${this.resource}/records/${this.remissionId}`)
                    .then(response => {
                        this.records = response.data.data
                    })

                this.$eventHub.$emit('reloadDataUnpaid')

            },
            clickAddRow() {

                this.records.push({
                    id: null,
                    date_of_payment: moment().format('YYYY-MM-DD'),
                    payment_method_type_id: null,
                    payment_destination_id:null,
                    reference: null,
                    payment: 0,
                    errors: {},
                    loading: false
                })

                this.showAddButton = false
            },
            clickCancel(index) {

                this.records.splice(index, 1)
                this.showAddButton = true

            },
            clickSubmit(index) {

                if(this.records[index].payment > parseFloat(this.remission.total_difference)) {
                    return this.$message.error('El monto ingresado supera al monto pendiente de pago, verifique.')
                }

                let form = {
                    id: this.records[index].id,
                    remission_id: this.remissionId,
                    date_of_payment: this.records[index].date_of_payment,
                    payment_method_type_id: this.records[index].payment_method_type_id,
                    payment_destination_id: this.records[index].payment_destination_id,
                    reference: this.records[index].reference,
                    filename: this.records[index].filename,
                    temp_path: this.records[index].temp_path,
                    payment: this.records[index].payment,
                }
                this.$http.post(`/${this.resource}`, form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.getData()
                            this.showAddButton = true
                            this.$eventHub.$emit('reloadData')
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.records[index].errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
            }, 
            close() {
                this.$emit('update:showDialog', false)
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>{
                        this.getData()
                        this.$eventHub.$emit('reloadData')
                    }
                )
            }
        }
    }
</script>
