<template>
    <div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-group" :class="{'has-danger': errors['deduction.advances']}">
                    <h4>Anticipos</h4>
                    <small class="form-control-feedback" v-if="errors['deduction.advances']" v-text="errors['deduction.advances'][0]"></small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group" :class="{'has-danger': errors['deduction.third_party_payments']}">
                    <h4>Pagos a terceros</h4>
                    <small class="form-control-feedback" v-if="errors['deduction.third_party_payments']" v-text="errors['deduction.third_party_payments'][0]"></small>
                </div>
            </div>

            <!-- Anticipos -->
            <div class="col-md-5">
                <table>
                    <thead>
                        <tr width="100%">
                            <template v-if="form.deduction.advances.length>0">
                                <th class="pb-2">Valor anticipo</th>
                            </template>
                            <th width="20%"><a href="#" @click.prevent="clickAddAdvance" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in form.deduction.advances" :key="index"> 
                            <td>
                                
                                <div class="form-group" v-if="errors[`deduction.advances.${index}.advance`]"  :class="{'has-danger': errors[`deduction.advances.${index}.advance`]}">
                                    <small class="form-control-feedback"  v-text="errors[`deduction.advances.${index}.advance`][0]"></small>
                                </div>

                                <div class="form-group mb-2 mr-2"  >
                                    <el-input-number v-model="row.advance" :min="0" controls-position="right" @change="changeAdvance(index)"></el-input-number>
                                </div>
                            </td>
                            <td class="series-table-actions text-center">
                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelAdvance(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <br>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-1">
            </div>
            <!-- Anticipos -->
            
            <!-- Pagos a terceros -->
            <div class="col-md-5">
                <table>
                    <thead>
                        <tr width="100%">
                            <template v-if="form.deduction.third_party_payments.length>0">
                                <th class="pb-2">Valor pago</th>
                            </template>
                            <th width="20%"><a href="#" @click.prevent="clickAddThirdPartyPayment" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in form.deduction.third_party_payments" :key="index"> 
                            <td>
                                
                                <div class="form-group" v-if="errors[`deduction.third_party_payments.${index}.third_party_payment`]"  :class="{'has-danger': errors[`deduction.third_party_payments.${index}.third_party_payment`]}">
                                    <small class="form-control-feedback"  v-text="errors[`deduction.third_party_payments.${index}.third_party_payment`][0]"></small>
                                </div>

                                <div class="form-group mb-2 mr-2"  >
                                    <el-input-number v-model="row.third_party_payment" :min="0" controls-position="right" @change="changeThirdPartyPayment(index)"></el-input-number>
                                </div>
                            </td>

                            <td class="series-table-actions text-center">
                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelThirdPartyPayment(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <br>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-1">
            </div>
            <!-- Pagos a terceros -->      
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-group" :class="{'has-danger': errors['deduction.other_deductions']}">
                    <h4>Otras deducciones</h4>
                    <small class="form-control-feedback" v-if="errors['deduction.other_deductions']" v-text="errors['deduction.other_deductions'][0]"></small>
                </div>
            </div>

            <div class="col-md-6">
                <!-- <div class="form-group" :class="{'has-danger': errors['deduction.third_party_payments']}">
                    <h4>Pagos a terceros</h4>
                    <small class="form-control-feedback" v-if="errors['deduction.third_party_payments']" v-text="errors['deduction.third_party_payments'][0]"></small>
                </div> -->
            </div>

            <!-- Otras deducciones -->
            <div class="col-md-5">
                <table>
                    <thead>
                        <tr width="100%">
                            <template v-if="form.deduction.other_deductions.length>0">
                                <th class="pb-2">Deducción</th>
                            </template>
                            <th width="20%"><a href="#" @click.prevent="clickAddOtherDeduction" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in form.deduction.other_deductions" :key="index"> 
                            <td>
                                
                                <div class="form-group" v-if="errors[`deduction.other_deductions.${index}.other_deduction`]"  :class="{'has-danger': errors[`deduction.other_deductions.${index}.other_deduction`]}">
                                    <small class="form-control-feedback"  v-text="errors[`deduction.other_deductions.${index}.other_deduction`][0]"></small>
                                </div>
                                <div class="form-group mb-2 mr-2"  >
                                    <el-input-number v-model="row.other_deduction" :min="0" controls-position="right" @change="changeOtherDeduction(index)"></el-input-number>
                                </div>
                            </td>
                            <td class="series-table-actions text-center">
                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelOtherDeduction(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <br>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-1">
            </div>
            <!-- Otras deducciones -->
            
        </div>

        <!-- Libranzas -->
        <div class="row mt-4">
            <template>
                <div class="col-md-12">
                    <div class="form-group" :class="{'has-danger': errors['deduction.orders']}">
                        <h4>Libranzas</h4>
                        <small class="form-control-feedback" v-if="errors['deduction.orders']" v-text="errors['deduction.orders'][0]"></small>
                    </div>
                </div> 

                <div class="col-md-12">
                    <table>
                        <thead>
                            <tr width="100%">
                                <template v-if="form.deduction.orders.length > 0">
                                    <th class="pb-2" width="75%">Descripción</th>
                                    <th class="pb-2" width="15%">Deducción</th>
                                </template>
                                <th width="10%"><a href="#" @click.prevent="clickAddOrder()" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in form.deduction.orders" :key="index"> 
                                <td>
                                    <div class="form-group" v-if="errors[`deduction.orders.${index}.description`]"  :class="{'has-danger': errors[`deduction.orders.${index}.description`]}">
                                        <small class="form-control-feedback"  v-text="errors[`deduction.orders.${index}.description`][0]"></small>
                                    </div>
                                    <div class="form-group mb-2 mr-2">
                                        <el-input v-model="row.description" ></el-input>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group" v-if="errors[`deduction.orders.${index}.deduction`]"  :class="{'has-danger': errors[`deduction.orders.${index}.deduction`]}">
                                        <small class="form-control-feedback"  v-text="errors[`deduction.orders.${index}.deduction`][0]"></small>
                                    </div>
                                    <div class="form-group mb-2 mr-2"  >
                                        <el-input-number v-model="row.deduction" :min="0" controls-position="right" @change="changeOrder"></el-input-number>
                                    </div>
                                </td> 

                                <td class="series-table-actions text-center" width="10%">
                                    <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelOrder(index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                <br>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
        <!-- Libranzas -->
    </div>

</template>

<script>

    // import {roundNumberFormat, getDiffInDays, getArrayStartEndDate, sumTotalFromArray} from '../../../helpers/functions'

    export default {
        props: ['showDialog', 'form', 'errors'],
        data() {
            return {
            }
        },
        async created() {
        },
        methods: {
            // anticipos
            clickAddAdvance(){
                this.form.deduction.advances.push({
                    advance :  0,
                })
            },
            clickCancelAdvance(index){
                this.form.deduction.advances.splice(index, 1)
                this.calculateTotalData()
            },
            changeAdvance(index){
                this.calculateTotalData()
            },
            // anticipos

            // otras deducciones
            clickAddOtherDeduction(){
                this.form.deduction.other_deductions.push({
                    other_deduction :  0,
                })
            },
            clickCancelOtherDeduction(index){
                this.form.deduction.other_deductions.splice(index, 1)
                this.calculateTotalData()
            },
            changeOtherDeduction(index){
                this.calculateTotalData()
            },
            // otras deducciones

            // pagos a terceros
            clickAddThirdPartyPayment(){

                this.form.deduction.third_party_payments.push({
                    third_party_payment :  0,
                })

            },
            clickCancelThirdPartyPayment(index){
                this.form.deduction.third_party_payments.splice(index, 1)
                this.calculateTotalData()
            },
            changeThirdPartyPayment(index){
                this.calculateTotalData()
            },
            // pagos a terceros

            // Libranzas
            clickAddOrder(){
                this.form.deduction.orders.push({
                    description: null,
                    deduction: 0,
                })
            },
            changeOrder(){
                this.calculateTotalData()
            },
            clickCancelOrder(index){
                this.form.deduction.orders.splice(index, 1)
                this.calculateTotalData()
            },
            // Libranzas
            
            calculateTotalData(){
                //emite evento para calcular total deducciones
                this.$emit('calculateTotalDeductionOthers')
            }
        }
    }
</script>
