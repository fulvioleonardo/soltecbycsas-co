<template>
    <div class="row mt-4">
        <!-- Licencia de Materinidad o Paternidad -->
        <template>
            <div class="col-md-12">
                <div class="form-group" :class="{'has-danger': errors['accrued.maternity_leave']}">
                    <h4>Licencia de Materinidad o Paternidad</h4>
                    <small class="form-control-feedback" v-if="errors['accrued.maternity_leave']" v-text="errors['accrued.maternity_leave'][0]"></small>
                </div>
            </div> 

            <div class="col-md-12">
                <table>
                    <thead>
                        <tr width="100%">
                            <template v-if="form.accrued.maternity_leave.length > 0">
                                <th class="pb-2">Fecha inicio - Fecha término</th>
                                <th class="pb-2">Cantidad</th>
                                <th class="pb-2">Pago</th>
                                <!-- <th class="pb-2"></th> -->
                            </template>
                            <th width="10%"><a href="#" @click.prevent="clickAddLicenses(form.accrued.maternity_leave, 'maternity')" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in form.accrued.maternity_leave" :key="index"> 
                            <td>
                                <div class="form-group mb-2 mr-2">
                                    <el-date-picker
                                        v-model="row.start_end_date"
                                        type="daterange"
                                        format="yyyy-MM-dd"
                                        value-format="yyyy-MM-dd"
                                        range-separator="H"
                                        :clearable="false"
                                        @change="changeLicensesStartEndDate(form.accrued.maternity_leave, index, 'maternity')"
                                        >
                                    </el-date-picker>
                                </div>
                            </td>
                            <td>
                                <div class="form-group" v-if="errors[`accrued.maternity_leave.${index}.quantity`]"  :class="{'has-danger': errors[`accrued.maternity_leave.${index}.quantity`]}">
                                    <small class="form-control-feedback"  v-text="errors[`accrued.maternity_leave.${index}.quantity`][0]"></small>
                                </div>
                                <div class="form-group mb-2 mr-2"  >
                                    <el-input-number v-model="row.quantity" :min="0" controls-position="right" disabled></el-input-number>
                                </div>
                            </td>
                            <td>
                                
                                <div class="form-group" v-if="errors[`accrued.maternity_leave.${index}.payment`]"  :class="{'has-danger': errors[`accrued.maternity_leave.${index}.payment`]}">
                                    <small class="form-control-feedback"  v-text="errors[`accrued.maternity_leave.${index}.payment`][0]"></small>
                                </div>
                                <div class="form-group mb-2 mr-2"  >
                                    <el-input-number v-model="row.payment" :min="0" disabled controls-position="right"></el-input-number>
                                </div>
                            </td> 

                            <td class="series-table-actions text-center" width="10%">
                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelLicenses(form.accrued.maternity_leave, index, 'maternity')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <br>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <!-- Licencia de Materinidad o Paternidad -->

        
        <!-- Licencia Remunerada -->
        <template>
            <div class="col-md-12 mt-4">
                <div class="form-group" :class="{'has-danger': errors['accrued.paid_leave']}">
                    <h4>Licencia Remunerada</h4>
                    <small class="form-control-feedback" v-if="errors['accrued.paid_leave']" v-text="errors['accrued.paid_leave'][0]"></small>
                </div>
            </div> 

            <div class="col-md-12">
                <table>
                    <thead>
                        <tr width="100%">
                            <template v-if="form.accrued.paid_leave.length > 0">
                                <th class="pb-2">Fecha inicio - Fecha término</th>
                                <th class="pb-2">Cantidad</th>
                                <th class="pb-2">Pago</th>
                                <!-- <th class="pb-2"></th> -->
                            </template>
                            <th width="10%"><a href="#" @click.prevent="clickAddLicenses(form.accrued.paid_leave, 'paid')" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in form.accrued.paid_leave" :key="index"> 
                            <td>
                                <div class="form-group mb-2 mr-2">
                                    <el-date-picker
                                        v-model="row.start_end_date"
                                        type="daterange"
                                        format="yyyy-MM-dd"
                                        value-format="yyyy-MM-dd"
                                        range-separator="H"
                                        :clearable="false"
                                        @change="changeLicensesStartEndDate(form.accrued.paid_leave, index, 'paid')"
                                        >
                                    </el-date-picker>
                                </div>
                            </td>
                            <td>
                                <div class="form-group" v-if="errors[`accrued.paid_leave.${index}.quantity`]"  :class="{'has-danger': errors[`accrued.paid_leave.${index}.quantity`]}">
                                    <small class="form-control-feedback"  v-text="errors[`accrued.paid_leave.${index}.quantity`][0]"></small>
                                </div>
                                <div class="form-group mb-2 mr-2"  >
                                    <el-input-number v-model="row.quantity" :min="0" controls-position="right" disabled></el-input-number>
                                </div>
                            </td>
                            <td>
                                
                                <div class="form-group" v-if="errors[`accrued.paid_leave.${index}.payment`]"  :class="{'has-danger': errors[`accrued.paid_leave.${index}.payment`]}">
                                    <small class="form-control-feedback"  v-text="errors[`accrued.paid_leave.${index}.payment`][0]"></small>
                                </div>
                                <div class="form-group mb-2 mr-2"  >
                                    <el-input-number v-model="row.payment" :min="0" disabled controls-position="right"></el-input-number>
                                </div>
                            </td> 

                            <td class="series-table-actions text-center" width="10%">
                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelLicenses(form.accrued.paid_leave, index, 'paid')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <br>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <!-- Licencia Remunerada -->

        
        <!-- Licencia No Remunerada -->
        <template>
            <div class="col-md-12 mt-4">
                <div class="form-group" :class="{'has-danger': errors['accrued.non_paid_leave']}">
                    <h4>Licencia No Remunerada</h4>
                    <small class="form-control-feedback" v-if="errors['accrued.non_paid_leave']" v-text="errors['accrued.non_paid_leave'][0]"></small>
                </div>
            </div> 

            <div class="col-md-12">
                <table>
                    <thead>
                        <tr width="100%">
                            <template v-if="form.accrued.non_paid_leave.length > 0">
                                <th class="pb-2">Fecha inicio - Fecha término</th>
                                <th class="pb-2">Cantidad</th>
                                <!-- <th class="pb-2"></th> -->
                            </template>
                            <th width="10%"><a href="#" @click.prevent="clickAddLicenses(form.accrued.non_paid_leave, 'non_paid')" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in form.accrued.non_paid_leave" :key="index"> 
                            <td>
                                <div class="form-group mb-2 mr-2">
                                    <el-date-picker
                                        v-model="row.start_end_date"
                                        type="daterange"
                                        format="yyyy-MM-dd"
                                        value-format="yyyy-MM-dd"
                                        range-separator="H"
                                        :clearable="false"
                                        @change="changeLicensesStartEndDate(form.accrued.non_paid_leave, index, 'non_paid')"
                                        >
                                    </el-date-picker>
                                </div>
                            </td>
                            <td>
                                <div class="form-group" v-if="errors[`accrued.non_paid_leave.${index}.quantity`]"  :class="{'has-danger': errors[`accrued.non_paid_leave.${index}.quantity`]}">
                                    <small class="form-control-feedback"  v-text="errors[`accrued.non_paid_leave.${index}.quantity`][0]"></small>
                                </div>
                                <div class="form-group mb-2 mr-2"  >
                                    <el-input-number v-model="row.quantity" :min="0" controls-position="right" disabled></el-input-number>
                                </div>
                            </td>

                            <td class="series-table-actions text-center" width="10%">
                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelLicenses(form.accrued.non_paid_leave, index, 'non_paid')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <br>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <!-- Licencia No Remunerada -->
    </div>
</template>

<script>

    import {roundNumberFormat, getDiffInDays, getArrayStartEndDate, sumTotalFromArray} from '../../../helpers/functions'

    export default {
        props: ['showDialog', 'form', 'errors', 'salaryValidation', 'getPaymentPerDay'],
        data() {
            return {
            }
        },
        async created() {
        },
        methods: {
            clickCancelLicenses(records, index){
                records.splice(index, 1)
                this.sumTotalLincese()
            },
            recalculateDataLicense(){
                
                this.form.accrued.maternity_leave.forEach((item, index) => {
                    this.calculateLicensePayment(this.form.accrued.maternity_leave, index, 'maternity')
                })

                this.form.accrued.paid_leave.forEach((item, index) => {
                    this.calculateLicensePayment(this.form.accrued.paid_leave, index, 'paid')
                })

                this.form.accrued.non_paid_leave.forEach((item, index) => {
                    this.calculateLicensePayment(this.form.accrued.non_paid_leave, index, 'non_paid')
                })
                
            },
            changeLicensesStartEndDate(records, index, type){

                const start_end_date = records[index].start_end_date
                const start_date = start_end_date[0]
                const end_date = start_end_date[1]
                const quantity = getDiffInDays(start_date, end_date)

                records[index].quantity = quantity
                records[index].start_date = start_date
                records[index].end_date = end_date

                this.calculateLicensePayment(records, index, type)

            },
            calculateLicensePayment(records, index, type){

                if(type !== 'non_paid')
                {
                    records[index].payment = this.getLicensePayment(records[index].quantity)
                    this.sumTotalLincese()
                }

            },
            getLicensePayment(quantity){
                return roundNumberFormat(quantity * this.getPaymentPerDay())
            },
            clickAddLicenses(records, type){

                const salary_validation = this.salaryValidation()
                if(!salary_validation.success) return this.$message.warning(salary_validation.message)

                const quantity = 1
                const date_range = getArrayStartEndDate(quantity)

                // licencia no remunerada
                if(type === 'non_paid')
                {
                    records.push({
                        start_date : date_range[0],
                        end_date : date_range[1],
                        quantity :  quantity,
                        start_end_date: date_range,
                    })

                    return
                }

                records.push({
                    start_date : date_range[0],
                    end_date : date_range[1],
                    quantity :  quantity,
                    start_end_date: date_range,
                    payment :  this.getLicensePayment(quantity)
                })

                this.sumTotalLincese()

            },
            sumTotalLincese(){
                
                let total_maternity_leave = sumTotalFromArray(this.form.accrued.maternity_leave, 'payment')
                let total_paid_leave = sumTotalFromArray(this.form.accrued.paid_leave, 'payment')

                this.form.accrued.total_license = roundNumberFormat(total_maternity_leave + total_paid_leave)

                this.$emit('sumTotalLincese')
            }
        }
    }
</script>
