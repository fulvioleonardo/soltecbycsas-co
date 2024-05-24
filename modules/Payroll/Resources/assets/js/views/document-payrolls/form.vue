<template>
    <div class="card mb-0 pt-2 pt-md-0" v-loading="loading">

        <div class="card-body" v-if="loading_form">
            <div class="invoice">
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                        </div>
                        <div class="row" v-if="isAdjustNote">
                            <div class="col-md-12">
                                <h4><b>Nómina de reemplazo ({{ form.number_full }})</b></h4>
                            </div>
                        </div>
                        <div class="row mt-4">

                            <div class="col-md-6 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.worker_id}">
                                    <label class="control-label">
                                        Empleados<span class="text-danger"> *</span>
                                        <el-tooltip class="item" effect="dark" content="Escribir al menos 3 caracteres para buscar" placement="top-start">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>

                                        <template v-if="!isAdjustNote">
                                            <a href="#" @click.prevent="showDialogNewWorker = true">[+ Nuevo]</a>
                                        </template>

                                    </label>
                                    <el-select v-model="form.worker_id" filterable remote class="border-left rounded-left border-info" popper-class="el-select-workers"
                                        placeholder="Escriba el nombre o número de documento del empleado"
                                        :remote-method="searchRemoteWorkers"
                                        :loading="loading_search"
                                        :disabled="isAdjustNote"
                                        @change="changeWorker">

                                        <el-option v-for="option in workers" :key="option.id" :value="option.id" :label="option.search_fullname"></el-option>

                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.worker_id" v-text="errors.worker_id[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-3 pb-2">
                                <div class="form-group" :class="{'has-danger': errors.type_document_id}">
                                    <label class="control-label">Resolución
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <el-select @change="changeResolution" v-model="form.type_document_id" class="border-left rounded-left border-info">
                                        <el-option v-for="option in resolutions" :key="option.id" :value="option.id" :label="`${option.prefix}`"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.type_document_id" v-text="errors.type_document_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.payroll_period_id}">
                                    <label class="control-label">Periodo de nómina<span class="text-danger"> *</span>
                                        <el-tooltip class="item" effect="dark" content="Frecuencia de pago" placement="top-start">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-select v-model="form.payroll_period_id"   filterable class="border-left rounded-left border-info" :disabled="form_disabled.payroll_period_id">
                                        <el-option v-for="option in payroll_periods" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.payroll_period_id" v-text="errors.payroll_period_id[0]"></small>
                                </div>
                            </div>

                        </div>


                        <el-tabs v-model="activeName">
                            <el-tab-pane label="Periodo" name="period">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['period.admision_date']}">
                                            <label class="control-label">Fecha de admisión<span class="text-danger"> *</span>
                                                <el-tooltip class="item" effect="dark" content="Fecha de inicio de labores del empleado" placement="top-start">
                                                    <i class="fa fa-info-circle"></i>
                                                </el-tooltip>
                                            </label>
                                            <el-date-picker v-model="form.period.admision_date" type="date" value-format="yyyy-MM-dd" :clearable="false" :disabled="form_disabled.admision_date"></el-date-picker>
                                            <small class="form-control-feedback" v-if="errors['period.admision_date']" v-text="errors['period.admision_date'][0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['period.settlement_start_date']}">
                                            <label class="control-label">F. Inicio de periodo de liquidación<span class="text-danger"> *</span></label>
                                            <el-date-picker v-model="form.period.settlement_start_date" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changePeriodSettlement"></el-date-picker>
                                            <small class="form-control-feedback" v-if="errors['period.settlement_start_date']" v-text="errors['period.settlement_start_date'][0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['period.settlement_end_date']}">
                                            <label class="control-label">F. Finalización de periodo de liquidación<span class="text-danger"> *</span></label>
                                            <el-date-picker v-model="form.period.settlement_end_date" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changePeriodSettlement"></el-date-picker>
                                            <small class="form-control-feedback" v-if="errors['period.settlement_end_date']" v-text="errors['period.settlement_end_date'][0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['period.issue_date']}">
                                            <label class="control-label">Fecha de emisión<span class="text-danger"> *</span></label>
                                            <el-date-picker v-model="form.period.issue_date" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                            <small class="form-control-feedback" v-if="errors['period.issue_date']" v-text="errors['period.issue_date'][0]"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['period.worked_time']}">
                                            <label class="control-label">Tiempo trabajado<span class="text-danger"> *</span></label>
                                            <el-input-number v-model="form.period.worked_time" :min="0" controls-position="right"></el-input-number>
                                            <small class="form-control-feedback" v-if="errors['period.worked_time']" v-text="errors['period.worked_time'][0]"></small>
                                        </div>
                                    </div>


                                </div>
                            </el-tab-pane>
                            <el-tab-pane label="Pagos" name="payments">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['payment.payment_method_id']}">
                                            <label class="control-label">Métodos de pago<span class="text-danger"> *</span></label>
                                            <el-select v-model="form.payment.payment_method_id"   filterable @change="changePaymentMethod" :disabled="form_disabled.payment">
                                                <el-option v-for="option in payment_methods" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                            </el-select>
                                            <small class="form-control-feedback" v-if="errors['payment.payment_method_id']" v-text="errors['payment.payment_method_id'][0]"></small>
                                        </div>
                                    </div>

                                    <template v-if="show_inputs_payment_method">
                                        <div class="col-md-3">
                                            <div class="form-group" :class="{'has-danger': errors['payment.bank_name']}">
                                                <label class="control-label">Nombre del banco</label>
                                                <el-input v-model="form.payment.bank_name" :disabled="form_disabled.payment"></el-input>
                                                <small class="form-control-feedback" v-if="errors['payment.bank_name']" v-text="errors['payment.bank_name'][0]"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" :class="{'has-danger': errors['payment.account_type']}">
                                                <label class="control-label">Tipo de cuenta</label>
                                                <el-input v-model="form.payment.account_type" :disabled="form_disabled.payment"></el-input>
                                                <small class="form-control-feedback" v-if="errors['payment.account_type']" v-text="errors['payment.account_type'][0]"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" :class="{'has-danger': errors['payment.account_number']}">
                                                <label class="control-label">Número de cuenta</label>
                                                <el-input v-model="form.payment.account_number" :disabled="form_disabled.payment"></el-input>
                                                <small class="form-control-feedback" v-if="errors['payment.account_number']" v-text="errors['payment.account_number'][0]"></small>
                                            </div>
                                        </div>
                                    </template>

                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group" :class="{'has-danger': errors['payment_dates']}">
                                            <h4>Fechas de pago<span class="text-danger"> *</span></h4>
                                            <small class="form-control-feedback" v-if="errors['payment_dates']" v-text="errors['payment_dates'][0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <table>
                                            <thead>
                                                <tr width="100%">
                                                    <th v-if="form.payment_dates.length>0" class="pb-2">Fecha<span class="text-danger"> *</span></th>
                                                    <th width="30%"><a href="#" @click.prevent="clickAddPaymentDate()" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(row, index) in form.payment_dates" :key="index">
                                                    <td>
                                                        <div class="form-group mb-2 mr-2"  >
                                                            <el-date-picker v-model="row.payment_date" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                                        </div>
                                                    </td>
                                                    <td class="series-table-actions text-center">
                                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelPaymentDate(index)">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                    <br>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </el-tab-pane>
                            <el-tab-pane label="Devengados" name="accrued">

                                <div class="row">

                                    <template v-if="isAdjustNote">
                                        <div class="col-md-3">
                                            <div class="form-group" :class="{'has-danger': errors['accrued.total_base_salary']}">
                                                <label class="control-label">Salario base
                                                    <span class="text-danger"> *</span>
                                                    <el-tooltip class="item" effect="dark" content="Salario base del empleado (equivalente a 30 días), no se afecta por los días trabajados" placement="top-start">
                                                        <i class="fa fa-info-circle"></i>
                                                    </el-tooltip>
                                                </label>
                                                <el-input-number v-model="form.accrued.total_base_salary" controls-position="right" @change="changeTotalBaseSalary"></el-input-number>
                                                <small class="form-control-feedback" v-if="errors['accrued.total_base_salary']" v-text="errors['accrued.total_base_salary'][0]"></small>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['accrued.worked_days']}">
                                            <label class="control-label">Días trabajados<span class="text-danger"> *</span></label>
                                            <el-input-number v-model="form.accrued.worked_days" :min="1" :max="30" :precision="0" controls-position="right" @change="changeWorkedDays"></el-input-number>
                                            <small class="form-control-feedback" v-if="errors['accrued.worked_days']" v-text="errors['accrued.worked_days'][0]"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['accrued.salary']}">
                                            <label class="control-label">Salario<span class="text-danger"> *</span></label>
                                            <el-input-number v-model="form.accrued.salary" :min="0" controls-position="right" disabled></el-input-number>
                                            <small class="form-control-feedback" v-if="errors['accrued.salary']" v-text="errors['accrued.salary'][0]"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['accrued.transportation_allowance']}">
                                            <label class="control-label">Subsidio de transporte</label>
                                            <el-input-number v-model="form.accrued.transportation_allowance" :min="0" :disabled="form_disabled.inputs_type_worker_sena" controls-position="right" @change="changeTransportationAllowance"></el-input-number>
                                            <small class="form-control-feedback" v-if="errors['accrued.transportation_allowance']" v-text="errors['accrued.transportation_allowance'][0]"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['accrued.accrued_total']}">
                                            <label class="control-label">Total devengados<span class="text-danger"> *</span></label>
                                            <el-input-number v-model="form.accrued.accrued_total" :min="0" controls-position="right" disabled></el-input-number>
                                            <small class="form-control-feedback" v-if="errors['accrued.accrued_total']" v-text="errors['accrued.accrued_total'][0]"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-md waves-effect waves-light btn-primary" @click.prevent="clickAddExtraHours">Agregar Horas Extras</button>
                                        </div>
                                    </div>
                                </div>


                                <el-tabs type="border-card" v-model="activeNameAccrued" class="mt-4">

                                    <el-tab-pane label="Vacaciones" name="accrued-vacations">

                                        <!-- Vacaciones disfrutadas -->
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.common_vacation']}">
                                                    <h4>Vacaciones disfrutadas</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.common_vacation']" v-text="errors['accrued.common_vacation'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.common_vacation.length > 0">
                                                                <th class="pb-2">Fecha inicio - Fecha término</th>
                                                                <th class="pb-2">N° de días</th>
                                                                <th class="pb-2">Pago</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="10%"><a href="#" @click.prevent="clickAddCommonVacation" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.common_vacation" :key="index">
                                                            <td>
                                                                <div class="form-group mb-2 mr-2">
                                                                    <el-date-picker
                                                                        v-model="row.start_end_date"
                                                                        type="daterange"
                                                                        format="yyyy-MM-dd"
                                                                        value-format="yyyy-MM-dd"
                                                                        range-separator="H"
                                                                        :clearable="false"
                                                                        @change="changeCommonVacationStartEndDate(index)"
                                                                        >
                                                                    </el-date-picker>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.common_vacation.${index}.quantity`]"  :class="{'has-danger': errors[`accrued.common_vacation.${index}.quantity`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.common_vacation.${index}.quantity`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.quantity" :min="0" controls-position="right" disabled></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>

                                                                <div class="form-group" v-if="errors[`accrued.common_vacation.${index}.payment`]"  :class="{'has-danger': errors[`accrued.common_vacation.${index}.payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.common_vacation.${index}.payment`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.payment" :min="0" controls-position="right" @change="changePaymentCommonVacation(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelCommonVacation(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Vacaciones disfrutadas -->

                                        <!-- Vacaciones compensadas -->
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.paid_vacation']}">
                                                    <h4>Vacaciones compensadas</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.paid_vacation']" v-text="errors['accrued.paid_vacation'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.paid_vacation.length > 0">
                                                                <th class="pb-2">Fecha inicio - Fecha término</th>
                                                                <th class="pb-2">N° de días</th>
                                                                <th class="pb-2">Pago</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="10%"><a href="#" @click.prevent="clickAddPaidVacation" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.paid_vacation" :key="index">
                                                            <td>
                                                                <div class="form-group mb-2 mr-2">
                                                                    <el-date-picker
                                                                        v-model="row.start_end_date"
                                                                        type="daterange"
                                                                        format="yyyy-MM-dd"
                                                                        value-format="yyyy-MM-dd"
                                                                        range-separator="H"
                                                                        :clearable="false"
                                                                        @change="changePaidVacationStartEndDate(index)"
                                                                        >
                                                                    </el-date-picker>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.paid_vacation.${index}.quantity`]"  :class="{'has-danger': errors[`accrued.paid_vacation.${index}.quantity`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.paid_vacation.${index}.quantity`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.quantity" :min="0" controls-position="right" ></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.paid_vacation.${index}.payment`]"  :class="{'has-danger': errors[`accrued.paid_vacation.${index}.payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.paid_vacation.${index}.payment`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.payment" :min="0" controls-position="right" @change="changePaymentPaidVacation(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelPaidVacation(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <!-- Vacaciones compensadas -->

                                    </el-tab-pane>
                                    <el-tab-pane label="Prestación social" name="accrued-social">

                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.service_bonus']}">
                                                    <h4>Prima de servicio</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.service_bonus']" v-text="errors['accrued.service_bonus'][0]"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.severance']}">
                                                    <h4>Cesantías</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.severance']" v-text="errors['accrued.severance'][0]"></small>
                                                </div>
                                            </div>

                                            <!-- Prima de servicio -->
                                            <div class="col-md-6">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.service_bonus.length>0">
                                                                <th class="pb-2">N° de días</th>
                                                                <th class="pb-2">Prima salarial</th>
                                                                <th class="pb-2">Prima no salarial</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="15%"><a href="#" @click.prevent="clickAddServiceBonus" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.service_bonus" :key="index">
                                                            <td>

                                                                <div class="form-group" v-if="errors[`accrued.service_bonus.${index}.quantity`]"  :class="{'has-danger': errors[`accrued.service_bonus.${index}.quantity`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.service_bonus.${index}.quantity`][0]"></small>
                                                                </div>

                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.quantity" :min="0" controls-position="right" @change="changeQuantityServiceBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>

                                                                <div class="form-group" v-if="errors[`accrued.service_bonus.${index}.payment`]"  :class="{'has-danger': errors[`accrued.service_bonus.${index}.payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.service_bonus.${index}.payment`][0]"></small>
                                                                </div>

                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.payment" :min="0" controls-position="right" @change="changePaymentServiceBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>

                                                                <div class="form-group" v-if="errors[`accrued.service_bonus.${index}.paymentNS`]"  :class="{'has-danger': errors[`accrued.service_bonus.${index}.paymentNS`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.service_bonus.${index}.paymentNS`][0]"></small>
                                                                </div>

                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.paymentNS" :min="0" controls-position="right" @change="changePaymentNSServiceBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelServiceBonus(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Prima de servicio -->

                                            <!-- Cesantías -->
                                            <div class="col-md-6">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.severance.length>0">
                                                                <th class="pb-2">Pago cesantías</th>
                                                                <th class="pb-2">% Interes</th>
                                                                <th class="pb-2">Pago intereses</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="15%"><a href="#" @click.prevent="clickAddSeverance" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.severance" :key="index">
                                                            <td>

                                                                <div class="form-group" v-if="errors[`accrued.severance.${index}.payment`]"  :class="{'has-danger': errors[`accrued.severance.${index}.payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.severance.${index}.payment`][0]"></small>
                                                                </div>

                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.payment" :min="0" controls-position="right" @change="calculateInterestPayment(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.severance.${index}.percentage`]"  :class="{'has-danger': errors[`accrued.severance.${index}.percentage`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.severance.${index}.percentage`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.percentage" :min="0" controls-position="right" @change="calculateInterestPayment(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.severance.${index}.interest_payment`]"  :class="{'has-danger': errors[`accrued.severance.${index}.interest_payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.severance.${index}.interest_payment`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.interest_payment" :min="0" controls-position="right" disabled></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelSeverance(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Cesantías -->
                                        </div>



                                    </el-tab-pane>

                                    <el-tab-pane label="Novedades" name="accrued-novelty">

                                        <!-- Incapacidades -->
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.work_disabilities']}">
                                                    <h4>Incapacidades</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.work_disabilities']" v-text="errors['accrued.work_disabilities'][0]"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.work_disabilities.length>0">
                                                                <th class="pb-2">Fecha inicio - Fecha término</th>
                                                                <th class="pb-2">Tipo</th>
                                                                <th class="pb-2">Cantidad</th>
                                                                <th class="pb-2">Pago</th>
                                                                <th class="pb-2"></th>
                                                            </template>
                                                            <th width="10%"><a href="#" @click.prevent="clickAddWorkDisability" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.work_disabilities" :key="index">
                                                            <td>
                                                                <div class="form-group mb-2 mr-2">
                                                                    <el-date-picker
                                                                        v-model="row.start_end_date"
                                                                        type="daterange"
                                                                        format="yyyy-MM-dd"
                                                                        value-format="yyyy-MM-dd"
                                                                        range-separator="H"
                                                                        :clearable="false"
                                                                        @change="changeWDisabilityStartEndDate(index)"
                                                                        >
                                                                    </el-date-picker>
                                                                </div>
                                                            </td>
                                                            <!-- <td>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-date-picker v-model="row.end_date" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                                                </div>
                                                            </td> -->
                                                            <td>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-select v-model="row.type" filterable>
                                                                        <el-option v-for="option in type_disabilities" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                                                    </el-select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.work_disabilities.${index}.quantity`]"  :class="{'has-danger': errors[`accrued.work_disabilities.${index}.quantity`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.work_disabilities.${index}.quantity`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.quantity" :min="0" controls-position="right" disabled></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.work_disabilities.${index}.payment`]"  :class="{'has-danger': errors[`accrued.work_disabilities.${index}.payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.work_disabilities.${index}.payment`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.payment" :min="0" controls-position="right" disabled></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <el-checkbox v-model="row.is_complete" @change="changeCompleteWorkDisability(index)">Completo</el-checkbox><br>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelWorkDisability(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Incapacidades -->

                                        <document-payroll-licenses
                                                :form="form"
                                                :errors="errors"
                                                :salaryValidation="salaryValidation"
                                                :getPaymentPerDay="getPaymentPerDay"
                                                @sumTotalLincese="sumTotalLincese"
                                                ref="componentDocumentPayrollLicenses"
                                        ></document-payroll-licenses>
                                    </el-tab-pane>

                                    <el-tab-pane label="Otros" name="accrued-others">

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.bonuses']}">
                                                    <h4>Bonificaciones</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.bonuses']" v-text="errors['accrued.bonuses'][0]"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group" :class="{'has-danger': errors['accrued.aid']}">
                                                    <h4>Ayudas</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.aid']" v-text="errors['accrued.aid'][0]"></small>
                                                </div>
                                            </div>

                                            <!-- Bonificaciones -->
                                            <div class="col-md-6">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.bonuses.length>0">
                                                                <th class="pb-2">Bonificación salarial</th>
                                                                <th class="pb-2">Bonificación no salarial</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="15%"><a href="#" @click.prevent="clickAddBonuses" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.bonuses" :key="index">
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.bonuses.${index}.salary_bonus`]"  :class="{'has-danger': errors[`accrued.bonuses.${index}.salary_bonus`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.bonuses.${index}.salary_bonus`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.salary_bonus" :min="0.01" controls-position="right" @change="changeSalaryBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.bonuses.${index}.non_salary_bonus`]"  :class="{'has-danger': errors[`accrued.bonuses.${index}.non_salary_bonus`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.bonuses.${index}.non_salary_bonus`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.non_salary_bonus" :min="0.01" controls-position="right" @change="changeSalaryBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelBonuses(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Bonificaciones -->

                                            <!-- Ayudas -->
                                            <div class="col-md-6">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.aid.length>0">
                                                                <th class="pb-2">Ayuda salarial</th>
                                                                <th class="pb-2">Ayuda no salarial</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="15%"><a href="#" @click.prevent="clickAddAid" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.aid" :key="index">
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.aid.${index}.salary_assistance`]"  :class="{'has-danger': errors[`accrued.aid.${index}.salary_assistance`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.aid.${index}.salary_assistance`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.salary_assistance" :min="0.01" controls-position="right" @change="changeSalaryAid(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.aid.${index}.non_salary_assistance`]"  :class="{'has-danger': errors[`accrued.aid.${index}.non_salary_assistance`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.aid.${index}.non_salary_assistance`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.non_salary_assistance" :min="0.01" controls-position="right" @change="changeSalaryAid(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelAid(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Ayudas -->
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.commissions']}">
                                                    <h4>Comisiones</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.commissions']" v-text="errors['accrued.commissions'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.third_party_payments']}">
                                                    <h4>Pagos a terceros</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.third_party_payments']" v-text="errors['accrued.third_party_payments'][0]"></small>
                                                </div>
                                            </div>

                                            <!-- Comisiones -->
                                            <div class="col-md-5">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.commissions.length>0">
                                                                <th class="pb-2">Valor comisión</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="20%"><a href="#" @click.prevent="clickAddCommission" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.commissions" :key="index">
                                                            <td>

                                                                <div class="form-group" v-if="errors[`accrued.commissions.${index}.commission`]"  :class="{'has-danger': errors[`accrued.commissions.${index}.commission`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.commissions.${index}.commission`][0]"></small>
                                                                </div>

                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.commission" :min="0" controls-position="right" @change="changeCommission(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelCommission(index)">
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
                                            <!-- Comisiones -->

                                            <!-- Pagos a terceros -->
                                            <div class="col-md-5">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.third_party_payments.length>0">
                                                                <th class="pb-2">Valor pago</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="20%"><a href="#" @click.prevent="clickAddThirdPartyPayment" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.third_party_payments" :key="index">
                                                            <td>

                                                                <div class="form-group" v-if="errors[`accrued.third_party_payments.${index}.third_party_payment`]"  :class="{'has-danger': errors[`accrued.third_party_payments.${index}.third_party_payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.third_party_payments.${index}.third_party_payment`][0]"></small>
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
                                                <div class="form-group" :class="{'has-danger': errors['accrued.advances']}">
                                                    <h4>Anticipos</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.advances']" v-text="errors['accrued.advances'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.compensations']}">
                                                    <h4>Compensaciones</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.compensations']" v-text="errors['accrued.compensations'][0]"></small>
                                                </div>
                                            </div>

                                            <!-- anticipo -->
                                            <div class="col-md-5">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.advances.length>0">
                                                                <th class="pb-2">Valor anticipo</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="20%"><a href="#" @click.prevent="clickAddAdvance" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.advances" :key="index">
                                                            <td>

                                                                <div class="form-group" v-if="errors[`accrued.advances.${index}.advance`]"  :class="{'has-danger': errors[`accrued.advances.${index}.advance`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.advances.${index}.advance`][0]"></small>
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
                                            <!-- anticipo -->

                                            <!-- Compensaciones -->
                                            <div class="col-md-6">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.compensations.length>0">
                                                                <th class="pb-2">Compensación ordinaria</th>
                                                                <th class="pb-2">Compensación extraordinaria</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="15%"><a href="#" @click.prevent="clickAddCompensation" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.compensations" :key="index">
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.compensations.${index}.ordinary_compensation`]"  :class="{'has-danger': errors[`accrued.compensations.${index}.ordinary_compensation`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.compensations.${index}.ordinary_compensation`][0]"></small>
                                                                </div>

                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.ordinary_compensation" :min="0" controls-position="right" @change="changeCompensation(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.compensations.${index}.extraordinary_compensation`]"  :class="{'has-danger': errors[`accrued.compensations.${index}.extraordinary_compensation`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.compensations.${index}.extraordinary_compensation`][0]"></small>
                                                                </div>

                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.extraordinary_compensation" :min="0" controls-position="right" @change="changeCompensation(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelCompensation(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Compensaciones -->
                                        </div>

                                        <div class="row mt-4">

                                            <div class="col-md-12">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.epctv_bonuses']}">
                                                    <h4>Bono EPCTVs</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.epctv_bonuses']" v-text="errors['accrued.epctv_bonuses'][0]"></small>
                                                </div>
                                            </div>

                                            <!-- Bono EPCTVs -->
                                            <div class="col-md-12">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.epctv_bonuses.length>0">
                                                                <th class="pb-2">Pago salarial</th>
                                                                <th class="pb-2">Pago no salarial</th>
                                                                <th class="pb-2">Pago alimentacion salarial</th>
                                                                <th class="pb-2">Pago alimentacion no salarial</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="10%"><a href="#" @click.prevent="clickAddEpctvBonus" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.epctv_bonuses" :key="index">
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.epctv_bonuses.${index}.paymentS`]"  :class="{'has-danger': errors[`accrued.epctv_bonuses.${index}.paymentS`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.epctv_bonuses.${index}.paymentS`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.paymentS" :min="0.01" controls-position="right" @change="changeEpctvBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.epctv_bonuses.${index}.paymentNS`]"  :class="{'has-danger': errors[`accrued.epctv_bonuses.${index}.paymentNS`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.epctv_bonuses.${index}.paymentNS`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.paymentNS" :min="0.01" controls-position="right" @change="changeEpctvBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.epctv_bonuses.${index}.salary_food_payment`]"  :class="{'has-danger': errors[`accrued.epctv_bonuses.${index}.salary_food_payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.epctv_bonuses.${index}.salary_food_payment`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.salary_food_payment" :min="0.01" controls-position="right" @change="changeEpctvBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.epctv_bonuses.${index}.non_salary_food_payment`]"  :class="{'has-danger': errors[`accrued.epctv_bonuses.${index}.non_salary_food_payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.epctv_bonuses.${index}.non_salary_food_payment`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.non_salary_food_payment" :min="0.01" controls-position="right" @change="changeEpctvBonus(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelEpctvBonus(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Bono EPCTVs -->

                                        </div>

                                        <!-- Otros conceptos -->
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.other_concepts']}">
                                                    <h4>Otros conceptos</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.other_concepts']" v-text="errors['accrued.other_concepts'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.other_concepts.length>0">
                                                                <th class="pb-2" width="60%">Concepto</th>
                                                                <th class="pb-2">Salarial</th>
                                                                <th class="pb-2">No salarial</th>
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="10%"><a href="#" @click.prevent="clickAddOtherConcepts" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.other_concepts" :key="index">
                                                            <td>

                                                                <template v-if="errors[`accrued.other_concepts.${index}.description_concept`]">
                                                                    <div class="form-group" :class="{'has-danger': errors[`accrued.other_concepts.${index}.description_concept`]}">
                                                                        <small class="form-control-feedback" v-text="errors[`accrued.other_concepts.${index}.description_concept`][0]"></small>
                                                                    </div>
                                                                </template>

                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input v-model="row.description_concept"></el-input>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.other_concepts.${index}.salary_concept`]"  :class="{'has-danger': errors[`accrued.other_concepts.${index}.salary_concept`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.other_concepts.${index}.salary_concept`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.salary_concept" :min="0.01" controls-position="right" @change="changeSalaryOtherConcepts(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.other_concepts.${index}.non_salary_concept`]"  :class="{'has-danger': errors[`accrued.other_concepts.${index}.non_salary_concept`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.other_concepts.${index}.non_salary_concept`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.non_salary_concept" :min="0.01" controls-position="right" @change="changeSalaryOtherConcepts(index)"></el-input-number>
                                                                </div>
                                                            </td>

                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelOtherConcepts(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <!-- Otros conceptos -->

                                        <!-- Huelgas Legales -->
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.legal_strike']}">
                                                    <h4>Huelgas Legales</h4>
                                                    <small class="form-control-feedback" v-if="errors['accrued.legal_strike']" v-text="errors['accrued.legal_strike'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.accrued.legal_strike.length > 0">
                                                                <th class="pb-2">Fecha inicio - Fecha término</th>
                                                                <th class="pb-2">Cantidad</th>
                                                                <!-- <th class="pb-2">Pago</th> -->
                                                                <!-- <th class="pb-2"></th> -->
                                                            </template>
                                                            <th width="10%"><a href="#" @click.prevent="clickAddLegalStrike()" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.accrued.legal_strike" :key="index">
                                                            <td>
                                                                <div class="form-group mb-2 mr-2">
                                                                    <el-date-picker
                                                                        v-model="row.start_end_date"
                                                                        type="daterange"
                                                                        format="yyyy-MM-dd"
                                                                        value-format="yyyy-MM-dd"
                                                                        range-separator="H"
                                                                        :clearable="false"
                                                                        @change="changeLegalStrikeStartEndDate(index)"
                                                                        >
                                                                    </el-date-picker>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`accrued.legal_strike.${index}.quantity`]"  :class="{'has-danger': errors[`accrued.legal_strike.${index}.quantity`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.legal_strike.${index}.quantity`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.quantity" :min="0" controls-position="right" disabled></el-input-number>
                                                                </div>
                                                            </td>
                                                            <!-- <td>

                                                                <div class="form-group" v-if="errors[`accrued.legal_strike.${index}.payment`]"  :class="{'has-danger': errors[`accrued.legal_strike.${index}.payment`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`accrued.legal_strike.${index}.payment`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.payment" :min="0" controls-position="right" @change="changeLegalStrikePayment(index)"></el-input-number>
                                                                </div>
                                                            </td>  -->

                                                            <td class="series-table-actions text-center" width="10%">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelLegalStrike(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Huelgas Legales -->

                                    </el-tab-pane>

                                    <el-tab-pane label="Opcionales" name="accrued-optional">
                                        <div class="row mt-2 mb-4">
                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.telecommuting']}">
                                                    <label class="control-label">Teletrabajo</label>
                                                    <el-input-number v-model="form.accrued.telecommuting" :min="0" controls-position="right" @change="changeOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['accrued.telecommuting']" v-text="errors['accrued.telecommuting'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.endowment']}">
                                                    <label class="control-label">Dotación</label>
                                                    <el-input-number v-model="form.accrued.endowment" :min="0" controls-position="right" @change="changeOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['accrued.endowment']" v-text="errors['accrued.endowment'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.sustenance_support']}">
                                                    <label class="control-label">Apoyo de sustento</label>
                                                    <el-input-number v-model="form.accrued.sustenance_support" :min="0" controls-position="right" @change="changeOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['accrued.sustenance_support']" v-text="errors['accrued.sustenance_support'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.withdrawal_bonus']}">
                                                    <label class="control-label">Bono de retiro</label>
                                                    <el-input-number v-model="form.accrued.withdrawal_bonus" :min="0" controls-position="right" @change="changeOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['accrued.withdrawal_bonus']" v-text="errors['accrued.withdrawal_bonus'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.compensation']}">
                                                    <label class="control-label">Indemnización</label>
                                                    <el-input-number v-model="form.accrued.compensation" :min="0" controls-position="right" @change="changeOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['accrued.compensation']" v-text="errors['accrued.compensation'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.salary_viatics']}">
                                                    <label class="control-label">Manutención y/o alojamiento</label>
                                                    <el-input-number v-model="form.accrued.salary_viatics" :min="0" controls-position="right" @change="changeOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['accrued.salary_viatics']" v-text="errors['accrued.salary_viatics'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.non_salary_viatics']}">
                                                    <label class="control-label">Manutención y/o alojamiento no salariales</label>
                                                    <el-input-number v-model="form.accrued.non_salary_viatics" :min="0" controls-position="right" @change="changeOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['accrued.non_salary_viatics']" v-text="errors['accrued.non_salary_viatics'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['accrued.refund']}">
                                                    <label class="control-label">Reintegro</label>
                                                    <el-input-number v-model="form.accrued.refund" :min="0" controls-position="right" @change="changeOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['accrued.refund']" v-text="errors['accrued.refund'][0]"></small>
                                                </div>
                                            </div>
                                        </div>

                                    </el-tab-pane>
                                </el-tabs>

                            </el-tab-pane>
                            <el-tab-pane label="Deducciones" name="deduction">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['deduction.eps_type_law_deductions_id']}">
                                            <label class="control-label">EPS - Deducciones por ley
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <el-select v-model="form.deduction.eps_type_law_deductions_id"   filterable @change="changeEpsTypeLawDeduction" :disabled="form_disabled.inputs_type_worker_sena">
                                                <el-option v-for="option in type_law_deductions" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                            </el-select>
                                            <small class="form-control-feedback" v-if="errors['deduction.eps_type_law_deductions_id']" v-text="errors['deduction.eps_type_law_deductions_id'][0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['deduction.eps_deduction']}">
                                            <label class="control-label">Deducción EPS
                                                <span v-if="getPercentageEpsTypeLawDeduction"> ({{ getPercentageEpsTypeLawDeduction.percentage }}%) </span>
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <el-input-number v-model="form.deduction.eps_deduction" :min="0" controls-position="right" @change="changeEpsDeduction" :disabled="form_disabled.inputs_type_worker_sena"></el-input-number>
                                            <small class="form-control-feedback" v-if="errors['deduction.eps_deduction']" v-text="errors['deduction.eps_deduction'][0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['deduction.pension_type_law_deductions_id']}">
                                            <label class="control-label">Pensión - Deducciones por ley<span class="text-danger"> *</span></label>
                                            <el-select v-model="form.deduction.pension_type_law_deductions_id"   filterable @change="changePensionTypeLawDeduction" :disabled="form_disabled.inputs_type_worker_sena || form_disabled.inputs_not_discount_pension">
                                                <el-option v-for="option in type_law_deductions" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                            </el-select>
                                            <small class="form-control-feedback" v-if="errors['deduction.pension_type_law_deductions_id']" v-text="errors['deduction.pension_type_law_deductions_id'][0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['deduction.pension_deduction']}">
                                            <label class="control-label">Deducción de pensión
                                                <span v-if="getPercentagePensionTypeLawDeduction"> ({{ getPercentagePensionTypeLawDeduction.percentage }}%) </span>
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <el-input-number v-model="form.deduction.pension_deduction" :min="0" controls-position="right" @change="changePensionDeduction" :disabled="form_disabled.inputs_type_worker_sena || form_disabled.inputs_not_discount_pension"></el-input-number>
                                            <small class="form-control-feedback" v-if="errors['deduction.pension_deduction']" v-text="errors['deduction.pension_deduction'][0]"></small>
                                        </div>
                                    </div>

                                    <!-- fondossp -->
                                    <template>

                                        <div class="col-md-3">
                                            <div class="form-group" :class="{'has-danger': errors['deduction.fondossp_type_law_deductions_id']}">
                                                <label class="control-label">Fondo de seguridad pensional</label>
                                                <el-select v-model="form.deduction.fondossp_type_law_deductions_id" clearable   filterable @change="changeFondosspTypeLawDeduction" :disabled="form_disabled.inputs_type_worker_sena">
                                                    <el-option v-for="option in type_law_deductions" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                                </el-select>
                                                <small class="form-control-feedback" v-if="errors['deduction.fondossp_type_law_deductions_id']" v-text="errors['deduction.fondossp_type_law_deductions_id'][0]"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" :class="{'has-danger': errors['deduction.fondosp_deduction_SP']}">
                                                <label class="control-label">Deducción de fondo SP
                                                    <span v-if="getPercentageFondosspTypeLawDeduction"> ({{ getPercentageFondosspTypeLawDeduction.percentage }}%) </span>
                                                </label>
                                                <el-input-number v-model="form.deduction.fondosp_deduction_SP" :min="0" controls-position="right" @change="changeFondosspDeduction" :disabled="form_disabled.inputs_type_worker_sena"></el-input-number>
                                                <small class="form-control-feedback" v-if="errors['deduction.fondosp_deduction_SP']" v-text="errors['deduction.fondosp_deduction_SP'][0]"></small>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group" :class="{'has-danger': errors['deduction.fondossp_sub_type_law_deductions_id']}">
                                                <label class="control-label">Fondo de subsistencia</label>
                                                <el-select v-model="form.deduction.fondossp_sub_type_law_deductions_id" clearable   filterable @change="changeFondosspSubTypeLawDeduction" :disabled="form_disabled.inputs_type_worker_sena">
                                                    <el-option v-for="option in type_law_deductions" :key="option.id" :value="option.id" :label="option.name"></el-option>
                                                </el-select>
                                                <small class="form-control-feedback" v-if="errors['deduction.fondossp_sub_type_law_deductions_id']" v-text="errors['deduction.fondossp_sub_type_law_deductions_id'][0]"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" :class="{'has-danger': errors['deduction.fondosp_deduction_sub']}">
                                                <label class="control-label">Deducción de fondo subsistencia
                                                    <span v-if="getPercentageFondosspSubTypeLawDeduction"> ({{ getPercentageFondosspSubTypeLawDeduction.percentage }}%) </span>
                                                </label>
                                                <el-input-number v-model="form.deduction.fondosp_deduction_sub" :min="0" controls-position="right" @change="changeFondosspSubDeduction" :disabled="form_disabled.inputs_type_worker_sena"></el-input-number>
                                                <small class="form-control-feedback" v-if="errors['deduction.fondosp_deduction_sub']" v-text="errors['deduction.fondosp_deduction_sub'][0]"></small>
                                            </div>
                                        </div>

                                    </template>
                                    <!-- fondossp -->

                                    <div class="col-md-3">
                                        <div class="form-group" :class="{'has-danger': errors['deduction.deductions_total']}">
                                            <label class="control-label">Total deducciones<span class="text-danger"> *</span></label>
                                            <el-input-number v-model="form.deduction.deductions_total" :min="0" controls-position="right" disabled></el-input-number>
                                            <small class="form-control-feedback" v-if="errors['deduction.deductions_total']" v-text="errors['deduction.deductions_total'][0]"></small>
                                        </div>
                                    </div>
                                </div>

                                <el-tabs type="border-card" v-model="activeNameDeduction" class="mt-4">
                                    <el-tab-pane label="Otros" name="deduction-others">

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h4>Sindicatos</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h4>Sanciones</h4>
                                                </div>
                                            </div>

                                            <!-- Sindicatos -->
                                            <div class="col-md-6">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.deduction.labor_union.length>0">
                                                                <th class="pb-2">Porcentaje</th>
                                                                <th class="pb-2">Deducción</th>
                                                            </template>
                                                            <th width="15%"><a href="#" @click.prevent="clickAddLaborUnion" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.deduction.labor_union" :key="index">
                                                            <td>
                                                                <div class="form-group" v-if="errors[`deduction.labor_union.${index}.percentage`]"  :class="{'has-danger': errors[`deduction.labor_union.${index}.percentage`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`deduction.labor_union.${index}.percentage`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.percentage" :min="0" controls-position="right" @change="changePercentageLaborUnion(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`deduction.labor_union.${index}.deduction`]"  :class="{'has-danger': errors[`deduction.labor_union.${index}.deduction`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`deduction.labor_union.${index}.deduction`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.deduction" :min="0" controls-position="right" disabled></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelLaborUnion(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Sindicatos -->

                                            <!-- Sanciones -->
                                            <div class="col-md-6">
                                                <table>
                                                    <thead>
                                                        <tr width="100%">
                                                            <template v-if="form.deduction.sanctions.length>0">
                                                                <th class="pb-2">Sanción pública</th>
                                                                <th class="pb-2">Sanción privada</th>
                                                            </template>
                                                            <th width="15%"><a href="#" @click.prevent="clickAddSanction" class="text-center font-weight-bold text-info pb-1 mt-1">[+ Agregar]</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(row, index) in form.deduction.sanctions" :key="index">
                                                            <td>
                                                                <div class="form-group" v-if="errors[`deduction.sanctions.${index}.public_sanction`]"  :class="{'has-danger': errors[`deduction.sanctions.${index}.public_sanction`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`deduction.sanctions.${index}.public_sanction`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.public_sanction" :min="0" controls-position="right" @change="changeSanction(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" v-if="errors[`deduction.sanctions.${index}.private_sanction`]"  :class="{'has-danger': errors[`deduction.sanctions.${index}.private_sanction`]}">
                                                                    <small class="form-control-feedback"  v-text="errors[`deduction.sanctions.${index}.private_sanction`][0]"></small>
                                                                </div>
                                                                <div class="form-group mb-2 mr-2"  >
                                                                    <el-input-number v-model="row.private_sanction" :min="0" controls-position="right" @change="changeSanction(index)"></el-input-number>
                                                                </div>
                                                            </td>
                                                            <td class="series-table-actions text-center">
                                                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelSanction(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                            <br>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Sanciones -->

                                        </div>

                                        <!-- separando campos en otro componente -->
                                        <document-payroll-deduction-others
                                                :form="form"
                                                :errors="errors"
                                                @calculateTotalDeductionOthers="calculateTotalDeductionOthers"
                                        ></document-payroll-deduction-others>

                                    </el-tab-pane>
                                    <el-tab-pane label="Opcionales" name="deduction-optionals">

                                        <!-- opcionales -->
                                        <div class="row mt-2 mb-2">

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.afc']}">
                                                    <label class="control-label">AFC</label>
                                                    <el-input-number v-model="form.deduction.afc" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.afc']" v-text="errors['deduction.afc'][0]"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.refund']}">
                                                    <label class="control-label">Reintegro</label>
                                                    <el-input-number v-model="form.deduction.refund" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.refund']" v-text="errors['deduction.refund'][0]"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.debt']}">
                                                    <label class="control-label">Deuda</label>
                                                    <el-input-number v-model="form.deduction.debt" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.debt']" v-text="errors['deduction.debt'][0]"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.education']}">
                                                    <label class="control-label">Educación</label>
                                                    <el-input-number v-model="form.deduction.education" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.education']" v-text="errors['deduction.education'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.voluntary_pension']}">
                                                    <label class="control-label">Pensión voluntaria</label>
                                                    <el-input-number v-model="form.deduction.voluntary_pension" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.voluntary_pension']" v-text="errors['deduction.voluntary_pension'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.withholding_at_source']}">
                                                    <label class="control-label">Retención fuente</label>
                                                    <el-input-number v-model="form.deduction.withholding_at_source" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.withholding_at_source']" v-text="errors['deduction.withholding_at_source'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.cooperative']}">
                                                    <label class="control-label">Cooperativa</label>
                                                    <el-input-number v-model="form.deduction.cooperative" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.cooperative']" v-text="errors['deduction.cooperative'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.tax_liens']}">
                                                    <label class="control-label">Embargo fiscal</label>
                                                    <el-input-number v-model="form.deduction.tax_liens" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.tax_liens']" v-text="errors['deduction.tax_liens'][0]"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group" :class="{'has-danger': errors['deduction.supplementary_plan']}">
                                                    <label class="control-label">Plan complementarios</label>
                                                    <el-input-number v-model="form.deduction.supplementary_plan" :min="0" controls-position="right" @change="changeDeductionOptionalInputs"></el-input-number>
                                                    <small class="form-control-feedback" v-if="errors['deduction.supplementary_plan']" v-text="errors['deduction.supplementary_plan'][0]"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- opcionales -->

                                    </el-tab-pane>

                                </el-tabs>

                            </el-tab-pane>
                        </el-tabs>

                    </div>


                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit" >Generar</el-button>
                    </div>
                </form>
            </div>

            <worker-form :showDialog.sync="showDialogNewWorker"
                        :external="true"></worker-form>


            <document-payroll-options :showDialog.sync="showDialogDocumentPayrollOptions"
                            :recordId="recordId"
                            :showDownload="true"
                            :showClose="false">
                            </document-payroll-options>

            <document-payroll-extra-hours :showDialog.sync="showDialogDocumentPayrollExtraHours"
                            :form="form"
                            :errors="errors"
                            @sumTotalsExtraHoursForm="sumTotalsExtraHoursForm"
                            ref="componentDocumentPayrollExtraHours"
                            >
                            </document-payroll-extra-hours>
        </div>
        <div class="card-body" v-else>
            <div class="invoice">
                <div class="row mt-5 mb-5">
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    import WorkerForm from '../workers/form.vue'
    import DocumentPayrollOptions from './partials/options.vue'
    import DocumentPayrollExtraHours from './partials/extra_hours.vue'
    import DocumentPayrollLicenses from './partials/licenses.vue'
    import DocumentPayrollDeductionOthers from './partials/deduction_others.vue'
    import {documentPayrollMixin} from '../../mixins/document_payroll'
    import {getValueIfNull} from '../../helpers/functions'

    export default {
        props: ['affected_document_payroll_id', 'type_payroll_adjust_note_id'],
        mixins: [documentPayrollMixin],
        components: {
            WorkerForm,
            DocumentPayrollOptions,
            DocumentPayrollExtraHours,
            DocumentPayrollLicenses,
            DocumentPayrollDeductionOthers
        },
        data() {
            return {
                resource: 'payroll/document-payrolls',
                resource_adjust_note: 'payroll/document-payroll-adjust-notes',
                loading_submit: false,
                loading_form: false,
                loading_search: false,
                errors: {},
                form: {},
                all_workers: [],
                workers: [],
                payroll_periods: [],
                payment_methods: [],
                type_law_deductions: [],
                type_documents: [],
                resolutions:[],
                type_disabilities: [],
                showDialogNewWorker: false,
                activeName: 'period',
                activeNameAccrued: 'accrued-vacations',
                activeNameDeduction: 'deduction-others',
                recordId:null,
                showDialogDocumentPayrollOptions:false,
                form_disabled: {},
                show_inputs_payment_method: true,
                advanced_configuration: {},
                showDialogDocumentPayrollExtraHours: false,
                quantity_days_month: 30,
                quantity_days_year: 360,
                loading: false
            }
        },
        async created() {

            await this.initForm()
            await this.getTables()
            await this.events()
            await this.checkDocumentPayrollAdjustNote()

            this.loading_form = true
        },
        computed: {
            getPercentageEpsTypeLawDeduction: function () {
                return this.getTypeLawDeduction(this.form.deduction.eps_type_law_deductions_id)
            },
            getPercentagePensionTypeLawDeduction: function () {
                return this.getTypeLawDeduction(this.form.deduction.pension_type_law_deductions_id)
            },
            getPercentageFondosspTypeLawDeduction: function () {
                return this.getTypeLawDeduction(this.form.deduction.fondossp_type_law_deductions_id)
            },
            getPercentageFondosspSubTypeLawDeduction: function () {
                return this.getTypeLawDeduction(this.form.deduction.fondossp_sub_type_law_deductions_id)
            },
            percentageWorkDisability: function() {
                return 66.67
            },
            isAdjustNote: function() {
                return !_.isEmpty(this.affected_document_payroll_id)
            }
        },
        methods: {
            async checkDocumentPayrollAdjustNote(){

                if(this.isAdjustNote)
                {
                    this.loading = true
                    await this.$http.get(`/${this.resource_adjust_note}/record/${this.affected_document_payroll_id}`).then(response => {

                        this.setFormDataAdjustNote(response.data.data)
                        // console.log(response)
                    })
                    .then(()=> {
                        this.loading = false
                    })
                }

            },
            setFormDataAdjustNote(data){

                this.form.period = data.period
                this.form.payroll_period_id = data.payroll_period_id
                this.form.worker_id = data.worker_id
                this.form.payment = data.payment
                this.form.payment_dates = data.payment_dates
                this.form.number_full = data.number_full
                this.form.type_payroll_adjust_note_id = this.type_payroll_adjust_note_id //tipo nómina ajuste (reemplazo)
                this.form.document_payroll_id = this.affected_document_payroll_id //id de nómina afectada

                this.reloadDataWorkers(this.form.worker_id)

                // devengados
                this.form.accrued.total_base_salary = parseFloat(data.worker_total_base_salary) //asignar salario base

                this.form.accrued.worked_days = data.accrued.worked_days
                this.form.accrued.salary = data.accrued.salary
                this.form.accrued.accrued_total = data.accrued.accrued_total

                this.form.accrued.transportation_allowance = getValueIfNull(data.accrued.transportation_allowance, undefined)
                this.form.accrued.telecommuting = getValueIfNull(data.accrued.telecommuting, undefined)
                this.form.accrued.endowment = getValueIfNull(data.accrued.endowment, undefined)
                this.form.accrued.sustenance_support = getValueIfNull(data.accrued.sustenance_support, undefined)
                this.form.accrued.withdrawal_bonus = getValueIfNull(data.accrued.withdrawal_bonus, undefined)
                this.form.accrued.compensation = getValueIfNull(data.accrued.compensation, undefined)
                this.form.accrued.salary_viatics = getValueIfNull(data.accrued.salary_viatics, undefined)
                this.form.accrued.non_salary_viatics = getValueIfNull(data.accrued.non_salary_viatics, undefined)
                this.form.accrued.refund = getValueIfNull(data.accrued.refund, undefined)

                this.form.accrued.work_disabilities = data.accrued.work_disabilities
                this.form.accrued.service_bonus = data.accrued.service_bonus
                this.form.accrued.severance = data.accrued.severance
                this.form.accrued.common_vacation = data.accrued.common_vacation
                this.form.accrued.paid_vacation = data.accrued.paid_vacation
                this.form.accrued.bonuses = data.accrued.bonuses
                this.form.accrued.aid = data.accrued.aid
                this.form.accrued.other_concepts = data.accrued.other_concepts
                this.form.accrued.maternity_leave = data.accrued.maternity_leave
                this.form.accrued.paid_leave = data.accrued.paid_leave
                this.form.accrued.non_paid_leave = data.accrued.non_paid_leave
                this.form.accrued.commissions = data.accrued.commissions
                this.form.accrued.advances = data.accrued.advances
                this.form.accrued.epctv_bonuses = data.accrued.epctv_bonuses
                this.form.accrued.third_party_payments = data.accrued.third_party_payments
                this.form.accrued.compensations = data.accrued.compensations
                this.form.accrued.legal_strike = data.accrued.legal_strike
                this.form.accrued.heds = data.accrued.heds
                this.form.accrued.hens = data.accrued.hens
                this.form.accrued.hrns = data.accrued.hrns
                this.form.accrued.heddfs = data.accrued.heddfs
                this.form.accrued.hrddfs = data.accrued.hrddfs
                this.form.accrued.hendfs = data.accrued.hendfs
                this.form.accrued.hrndfs = data.accrued.hrndfs

                // devengados


                // deducciones

                this.form.deduction.eps_type_law_deductions_id = data.deduction.eps_type_law_deductions_id
                this.form.deduction.eps_deduction = data.deduction.eps_deduction
                this.form.deduction.pension_type_law_deductions_id = data.deduction.pension_type_law_deductions_id
                this.form.deduction.pension_deduction = data.deduction.pension_deduction
                this.form.deduction.deductions_total = data.deduction.deductions_total

                this.form.deduction.fondossp_type_law_deductions_id = getValueIfNull(data.deduction.fondossp_type_law_deductions_id, undefined)
                this.form.deduction.fondosp_deduction_SP = getValueIfNull(data.deduction.fondosp_deduction_SP, undefined)
                this.form.deduction.fondossp_sub_type_law_deductions_id = getValueIfNull(data.deduction.fondossp_sub_type_law_deductions_id, undefined)
                this.form.deduction.fondosp_deduction_sub = getValueIfNull(data.deduction.fondosp_deduction_sub, undefined)

                this.form.deduction.afc = getValueIfNull(data.deduction.afc, undefined)
                this.form.deduction.refund = getValueIfNull(data.deduction.refund, undefined)
                this.form.deduction.debt = getValueIfNull(data.deduction.debt, undefined)
                this.form.deduction.education = getValueIfNull(data.deduction.education, undefined)

                this.form.deduction.voluntary_pension = getValueIfNull(data.deduction.voluntary_pension, undefined)
                this.form.deduction.withholding_at_source = getValueIfNull(data.deduction.withholding_at_source, undefined)
                this.form.deduction.cooperative = getValueIfNull(data.deduction.cooperative, undefined)
                this.form.deduction.tax_liens = getValueIfNull(data.deduction.tax_liens, undefined)
                this.form.deduction.supplementary_plan = getValueIfNull(data.deduction.supplementary_plan, undefined)

                this.form.deduction.labor_union = data.deduction.labor_union
                this.form.deduction.sanctions = data.deduction.sanctions
                this.form.deduction.orders = data.deduction.orders
                this.form.deduction.third_party_payments = data.deduction.third_party_payments
                this.form.deduction.advances = data.deduction.advances
                this.form.deduction.other_deductions = data.deduction.other_deductions

                // deducciones

            },
            async changeTotalBaseSalary(){

                this.form.accrued.salary = this.form.accrued.total_base_salary
                //recalcular campos que utilizan el salario base del empleado para calculos, estos se ven afectados por el mismo
                await this.recalculateData()

                // await this.calculateTotal()

                await this.changeWorkedDays()

            },
            getValueFromInputUndefined(value){
                return value ? value : 0 //obtener el valor de un input que puede ser un campo undefined
            },
            getTypeLawDeduction(id){
                return _.find(this.type_law_deductions, { id : id })
            },
            percentageToFactor(percentage){
                return percentage / 100
            },
            getTotalLawDeduction(type_law_deductions_id){

                let type_law_deduction = this.getTypeLawDeduction(type_law_deductions_id)

                // total devengados – subsidio transporte *  % deducciones por ley (type law deductions)
                return _.round((this.form.accrued.accrued_total - this.getValueFromInputUndefined(this.form.accrued.transportation_allowance)) * this.percentageToFactor(type_law_deduction.percentage), 2)

            },
            changeEpsTypeLawDeduction(){
                this.form.deduction.eps_deduction = this.getTotalLawDeduction(this.form.deduction.eps_type_law_deductions_id)
                this.calculateTotalDeduction()
            },
            changePensionTypeLawDeduction(){
                this.form.deduction.pension_deduction = this.getTotalLawDeduction(this.form.deduction.pension_type_law_deductions_id)
                this.calculateTotalDeduction()
            },
            changeFondosspTypeLawDeduction(){

                if(this.form.deduction.fondossp_type_law_deductions_id)
                {
                    this.form.deduction.fondosp_deduction_SP = this.getTotalLawDeduction(this.form.deduction.fondossp_type_law_deductions_id)
                }
                else
                {
                    this.form.deduction.fondosp_deduction_SP = undefined
                }

                this.calculateTotalDeduction()

            },
            changeFondosspSubTypeLawDeduction(){

                if(this.form.deduction.fondossp_sub_type_law_deductions_id)
                {
                    this.form.deduction.fondosp_deduction_sub = this.getTotalLawDeduction(this.form.deduction.fondossp_sub_type_law_deductions_id)
                }
                else
                {
                    this.form.deduction.fondosp_deduction_sub = undefined
                }

                this.calculateTotalDeduction()

            },
            calculateTotalTypeLawDeduction(){

                // si el tipo de trabajador es relacionado al sena, no tiene dscto de pension ni salud

                if(this.form.select_worker.is_type_worker_sena)
                {
                    this.form.deduction.eps_type_law_deductions_id = 1
                    this.form.deduction.pension_type_law_deductions_id = 5

                    this.form.deduction.eps_deduction = 0
                    this.form.deduction.pension_deduction = 0

                    this.form_disabled.inputs_type_worker_sena = true

                    this.form.deduction.fondossp_type_law_deductions_id = undefined
                    this.form.deduction.fondosp_deduction_SP = undefined
                    this.form.deduction.fondossp_sub_type_law_deductions_id = undefined
                    this.form.deduction.fondosp_deduction_sub = undefined
                }
                // si es de subtipo dependiente pensionado, no aplica descuento de pension
                else if(!this.form.select_worker.discount_pension)
                {
                    this.form.deduction.pension_type_law_deductions_id = 5
                    this.form.deduction.pension_deduction = 0
                    this.form_disabled.inputs_not_discount_pension = true
                    this.form_disabled.inputs_type_worker_sena = false
                    this.changeEpsTypeLawDeduction()
                }
                else
                {
                    //calcular eps y pension
                    this.form_disabled.inputs_type_worker_sena = false
                    this.form_disabled.inputs_not_discount_pension = false
                    this.changeEpsTypeLawDeduction()
                    this.changePensionTypeLawDeduction()

                    this.changeFondosspTypeLawDeduction()
                    this.changeFondosspSubTypeLawDeduction()
                }

            },
            clickAddExtraHours(){

                if(parseFloat(this.form.accrued.salary) <= 0 || parseFloat(this.form.accrued.total_base_salary) <= 0){
                    return this.$message.warning('El campo Salario debe ser mayor a 0')
                }

                this.showDialogDocumentPayrollExtraHours = true
            },
            changePaymentMethod(){

                //mostrar campos adicionales, si el metodo de pago es el definido en el arreglo/obligatorio
                this.show_inputs_payment_method = [2,3,4,5,6,7,21,22,30,31,42,45,46,47].includes(this.form.payment.payment_method_id)

            },
            changePeriodSettlement(){

                this.form.period.worked_time = moment(this.form.period.settlement_end_date).diff(moment(this.form.period.settlement_start_date), 'days', true)

            },
            setInitialDataPeriod(){

                let last_month = moment().subtract(1, 'months')

                this.form.period.settlement_start_date = last_month.startOf('month').format('YYYY-MM-DD')
                this.form.period.settlement_end_date = last_month.endOf('month').format('YYYY-MM-DD')
                this.form.period.worked_time = this.quantity_days_month

            },
            initForm() {

                this.form = {
                    type_document_id: null,
                    prefix: null,
                    number_full: null,
                    period: {
                        admision_date: moment().format('YYYY-MM-DD'),
                        settlement_start_date: null,
                        settlement_end_date: null,
                        worked_time: 0,
                        issue_date: moment().format('YYYY-MM-DD'),
                    },
                    payroll_period_id: null,
                    worker_id: null,
                    select_worker: {}, //se usa para asignar el empleado seleccionado - solo en interfaz
                    resolution_number: null,
                    payment: {
                        payment_method_id: null,
                        bank_name: null,
                        account_type: null,
                        account_number: null,
                    },
                    payment_dates: [],
                    accrued :{
                        worked_days: this.quantity_days_month,
                        total_base_salary: 0, //salario base del empleado (equivalente a 30 dias), no se afecta por los dias trabajados
                        salary: 0,
                        accrued_total: 0,
                        transportation_allowance: undefined, //se usa undefined ya que el componente input-number le asigna 0 al valor null
                        telecommuting: undefined,
                        endowment: undefined,
                        sustenance_support: undefined,
                        withdrawal_bonus: undefined,
                        compensation: undefined,

                        salary_viatics: undefined,
                        non_salary_viatics: undefined,
                        refund: undefined,

                        work_disabilities: [],
                        service_bonus: [],
                        severance: [],
                        common_vacation: [],
                        paid_vacation: [],
                        bonuses: [],
                        aid: [],
                        other_concepts: [],
                        maternity_leave: [],
                        paid_leave: [],
                        non_paid_leave: [],
                        commissions: [],
                        advances: [],
                        epctv_bonuses: [],
                        third_party_payments: [],
                        compensations: [],
                        legal_strike: [],
                        heds: [],
                        hens: [],
                        hrns: [],
                        heddfs: [],
                        hrddfs: [],
                        hendfs: [],
                        hrndfs: [],
                        total_extra_hours: 0, //usado para controlar los totales de horas extras en vista y sumar al total devengados
                        total_license: 0, //usado para controlar los totales de licencias en vista y sumar al total devengados
                    },
                    deduction: {
                        eps_type_law_deductions_id: 1,
                        eps_deduction: 0,
                        pension_type_law_deductions_id: 5,
                        pension_deduction: 0,
                        deductions_total: 0,

                        fondossp_type_law_deductions_id: undefined,
                        fondosp_deduction_SP: undefined,
                        fondossp_sub_type_law_deductions_id: undefined,
                        fondosp_deduction_sub: undefined,

                        afc: undefined,
                        refund: undefined,
                        debt: undefined,
                        education: undefined,

                        voluntary_pension: undefined,
                        withholding_at_source: undefined,
                        cooperative: undefined,
                        tax_liens: undefined,
                        supplementary_plan: undefined,

                        labor_union: [],
                        sanctions: [],
                        orders: [],
                        third_party_payments: [],
                        advances: [],
                        other_deductions: [],
                    },
                }

                this.errors = {}


                this.form_disabled = {
                    payroll_period_id : false,
                    admision_date : false,
                    payment : false,
                    inputs_type_worker_sena : false,
                    inputs_not_discount_pension: false,
                }

                this.setInitialDataPeriod()
            },
            roundNumber(number, decimals = 2){
                return _.round(number, decimals)
            },
            salaryValidation(){

                if(parseFloat(this.form.accrued.salary) <= 0 || parseFloat(this.form.accrued.total_base_salary) <= 0){
                    return {
                        success : false,
                        message : 'El campo Salario debe ser mayor a 0'
                    }
                }

                return {
                    success : true
                }
            },
            // sindicatos
            recalculateLaborUnion(){

                this.form.deduction.labor_union.forEach((element, index) => {
                    this.setDeductionLaborUnion(index)
                })

            },
            changePercentageLaborUnion(index){

                this.setDeductionLaborUnion(index)
                this.calculateTotalDeduction()

            },
            setDeductionLaborUnion(index){
                this.form.deduction.labor_union[index].deduction = this.roundNumber(this.form.accrued.total_base_salary * this.percentageToFactor(this.form.deduction.labor_union[index].percentage))
            },
            clickAddLaborUnion(){

                const salary_validation = this.salaryValidation()
                if(!salary_validation.success) return this.$message.warning(salary_validation.message)

                this.form.deduction.labor_union.push({
                    percentage :  0,
                    deduction :  0,
                })

            },
            clickCancelLaborUnion(index){
                this.form.deduction.labor_union.splice(index, 1)
                this.calculateTotalDeduction()
            },
            // sindicatos
            // sanciones
            changeSanction(index){
                this.calculateTotalDeduction()
            },
            clickAddSanction(){

                this.form.deduction.sanctions.push({
                    public_sanction :  0,
                    private_sanction :  0,
                })

            },
            clickCancelSanction(index){
                this.form.deduction.sanctions.splice(index, 1)
                this.calculateTotalDeduction()
            },
            // sanciones

            // incapacidades
            recalculateWorkDisability(){

                this.form.accrued.work_disabilities.forEach((element, index) => {

                    let payment = this.getPaymentWorkDisability(element.quantity, element.is_complete)
                    this.form.accrued.work_disabilities[index].payment =  payment

                })

            },
            changeWDisabilityStartEndDate(index){
                this.calculateWDisabilityStartEndDate(index)
            },
            changeCompleteWorkDisability(index){
                this.calculateWDisabilityStartEndDate(index)
            },
            calculateWDisabilityStartEndDate(index){

                const start_end_date = this.form.accrued.work_disabilities[index].start_end_date
                const start_date = start_end_date[0]
                const end_date = start_end_date[1]
                const quantity = this.roundNumber(moment(end_date, "YYYY-MM-DD").diff(moment(start_date, "YYYY-MM-DD"), 'days', true))
                let payment = this.getPaymentWorkDisability(quantity, this.form.accrued.work_disabilities[index].is_complete)

                //calcular valores finales
                this.form.accrued.work_disabilities[index].start_date = start_date
                this.form.accrued.work_disabilities[index].end_date = end_date
                this.form.accrued.work_disabilities[index].quantity = quantity
                this.form.accrued.work_disabilities[index].payment =  payment

                this.calculateTotal()

            },
            getPaymentWorkDisability(quantity, is_complete = false){

                let payment = is_complete ? (this.getPaymentPerDay() * quantity) : (this.getPaymentPerDay() * this.percentageToFactor(this.percentageWorkDisability) * quantity)

                return this.roundNumber(payment)
            },
            clickAddWorkDisability(){

                const salary_validation = this.salaryValidation()
                if(!salary_validation.success) return this.$message.warning(salary_validation.message)

                const quantity = 1
                const start_date = moment().format('YYYY-MM-DD')
                const end_date = moment().add(quantity, 'days').format('YYYY-MM-DD')
                const type = (this.type_disabilities.length > 0) ? this.type_disabilities[0].id : null
                let payment = this.getPaymentWorkDisability(quantity)

                this.form.accrued.work_disabilities.push({
                    is_complete :  false,
                    start_date :  start_date,
                    end_date :  end_date,
                    type :  type,
                    quantity :  quantity,
                    payment :  payment,
                    start_end_date: [
                        start_date,
                        end_date
                    ]
                })

                this.calculateTotal()

            },
            clickCancelWorkDisability(index){
                this.form.accrued.work_disabilities.splice(index, 1)
                this.calculateTotal()
            },
            // incapacidades

            clickAddPaymentDate(param_payment_date = null) {

                let payment_date = param_payment_date ? param_payment_date : moment().format('YYYY-MM-DD')

                this.form.payment_dates.push({
                    payment_date: payment_date
                })

            },
            clickCancelPaymentDate(index) {
                this.form.payment_dates.splice(index, 1)
            },
            events(){

                this.$eventHub.$on('reloadDataWorkers', (worker_id) => {
                    this.reloadDataWorkers(worker_id)
                })

            },
            async getTables(){

                const url = (this.isAdjustNote) ? `/${this.resource_adjust_note}/tables/${this.type_payroll_adjust_note_id}` : `/${this.resource}/tables`

                this.loading = true

                await this.$http.get(`${url}`)
                    .then(response => {

                        this.all_workers = response.data.workers
                        this.workers = response.data.workers

                        this.payroll_periods = response.data.payroll_periods
                        this.payment_methods = response.data.payment_methods
                        this.type_law_deductions = response.data.type_law_deductions
                        // this.type_documents = response.data.type_documents
                        this.resolutions = response.data.resolutions
                        this.type_disabilities = response.data.type_disabilities
                        this.advanced_configuration = response.data.advanced_configuration

                        this.filterWorkers();
                    })
                    .then(()=> {
                        this.loading = false
                    })
            },
            changeResolution(){

                let resolution = _.find(this.resolutions, {id : this.form.type_document_id})
                if(resolution) {
                    this.form.prefix = resolution.prefix
                    this.form.resolution_number = resolution.resolution_number
                }

            },
            searchRemoteWorkers(input) {

                if (input.length > 2) {

                    this.loading_search = true
                    let parameters = `input=${input}`

                    this.$http.get(`/payroll/workers/search?${parameters}`)
                            .then(response => {
                                this.workers = response.data.workers
                                this.loading_search = false

                                if(this.workers.length == 0){
                                    this.filterWorkers()
                                }
                            })
                } else {
                    this.filterWorkers()
                }

            },
            resetForm() {
                this.activeName = 'period'
                this.initForm()
            },
            filterWorkers() {
                this.workers = this.all_workers
            },
            close() {
                location.href = `/${this.resource}`
            },
            reloadDataWorkers(worker_id) {
                this.$http.get(`/payroll/workers/search-by-id/${worker_id}`).then((response) => {
                    this.workers = response.data.workers
                    this.form.worker_id = worker_id
                    this.changeWorker()
                })
            },
            changePensionDeduction(){
                // this.calculateTotal()
                this.calculateTotalDeduction()
            },
            changeFondosspDeduction(){
                this.calculateTotalDeduction()
            },
            changeFondosspSubDeduction(){
                this.calculateTotalDeduction()
            },
            changeEpsDeduction(){
                // this.calculateTotal()
                this.calculateTotalDeduction()
            },
            calculateTotal(){
                this.calculateTotalAccrued()
                this.calculateTotalTypeLawDeduction()
                this.calculateTotalDeduction()
            },
            calculateTotalDeductionOthers(){
                this.calculateTotalDeduction()
            },
            calculateTotalDeduction(){

                let total_labor_union = _.sumBy(this.form.deduction.labor_union, 'deduction')

                let total_sanctions = this.sumValueFromArray(this.form.deduction.sanctions, 'private_sanction') + this.sumValueFromArray(this.form.deduction.sanctions, 'public_sanction')

                let total_orders = this.sumValueFromArray(this.form.deduction.orders, 'deduction')

                let total_third_party_payments = this.sumValueFromArray(this.form.deduction.third_party_payments, 'third_party_payment')

                let total_advances = this.sumValueFromArray(this.form.deduction.advances, 'advance')

                let total_other_deductions = this.sumValueFromArray(this.form.deduction.other_deductions, 'other_deduction')

                let total_deduction_optional_inputs = this.getTotalDeductionOptionalInputs()

                let fondosp_deduction_SP = this.getValueFromInputUndefined(this.form.deduction.fondosp_deduction_SP)

                let fondosp_deduction_sub = this.getValueFromInputUndefined(this.form.deduction.fondosp_deduction_sub)


                this.form.deduction.deductions_total = this.roundNumber(
                                                            parseFloat(this.form.deduction.eps_deduction)
                                                            + parseFloat(this.form.deduction.pension_deduction)
                                                            + total_labor_union
                                                            + total_sanctions
                                                            + total_orders
                                                            + total_third_party_payments
                                                            + total_advances
                                                            + total_other_deductions
                                                            + total_deduction_optional_inputs
                                                            + fondosp_deduction_SP
                                                            + fondosp_deduction_sub
                                                        )

            },
            sumValueFromArray(array, property){
                return _.sumBy(array, property)
            },
            sumValueArrayValidateProperty(array, property){
                return _.sumBy(array, (row) => {
                    return this.getValueFromInputUndefined(row[property])
                })
            },
            sumEpctvBonuses(){
                return _.sumBy(this.form.accrued.epctv_bonuses, (row) => {
                    return this.getValueFromInputUndefined(row['paymentS']) + this.getValueFromInputUndefined(row['paymentNS']) + this.getValueFromInputUndefined(row['salary_food_payment']) + this.getValueFromInputUndefined(row['non_salary_food_payment'])
                })
            },
            calculateTotalAccrued(){

                // sum_accrued

                let total_work_disability = this.sumValueFromArray(this.form.accrued.work_disabilities, 'payment')

                let total_service_bonus = this.sumValueFromArray(this.form.accrued.service_bonus, 'payment') + this.sumValueArrayValidateProperty(this.form.accrued.service_bonus, 'paymentNS')

                let total_severance = this.sumValueFromArray(this.form.accrued.severance, 'payment') + this.sumValueFromArray(this.form.accrued.severance, 'interest_payment')

                let total_common_vacation = this.sumValueFromArray(this.form.accrued.common_vacation, 'payment')

                let total_paid_vacation = this.sumValueFromArray(this.form.accrued.paid_vacation, 'payment')

                let total_bonuses = this.sumValueArrayValidateProperty(this.form.accrued.bonuses, 'salary_bonus') + this.sumValueArrayValidateProperty(this.form.accrued.bonuses, 'non_salary_bonus')

                let total_aid = this.sumValueArrayValidateProperty(this.form.accrued.aid, 'salary_assistance') + this.sumValueArrayValidateProperty(this.form.accrued.aid, 'non_salary_assistance')

                let total_other_concepts = this.sumValueArrayValidateProperty(this.form.accrued.other_concepts, 'salary_concept') + this.sumValueArrayValidateProperty(this.form.accrued.other_concepts, 'non_salary_concept')

                let total_commissions = this.sumValueFromArray(this.form.accrued.commissions, 'commission')

                let total_epctv_bonuses = this.sumEpctvBonuses()

                let total_third_party_payments = this.sumValueArrayValidateProperty(this.form.accrued.third_party_payments, 'third_party_payment')

                let total_advances = this.sumValueFromArray(this.form.accrued.advances, 'advance')

                let total_compensations = this.sumValueFromArray(this.form.accrued.compensations, 'ordinary_compensation') + this.sumValueFromArray(this.form.accrued.compensations, 'extraordinary_compensation')

                // let total_legal_strike = this.sumValueFromArray(this.form.accrued.legal_strike, 'payment')

                let total_optional_inputs = this.getTotalOptionalInputs()


                this.form.accrued.accrued_total = this.roundNumber(
                                                    parseFloat(this.form.accrued.salary)
                                                    + (this.form.accrued.transportation_allowance ? parseFloat(this.form.accrued.transportation_allowance) : 0)
                                                    + this.form.accrued.total_extra_hours
                                                    + total_work_disability
                                                    + total_service_bonus
                                                    + total_severance
                                                    + total_common_vacation
                                                    + total_paid_vacation
                                                    + total_bonuses
                                                    + total_aid
                                                    + total_other_concepts
                                                    + this.form.accrued.total_license
                                                    + total_commissions
                                                    + total_epctv_bonuses
                                                    + total_third_party_payments
                                                    + total_advances
                                                    + total_compensations
                                                    // + total_legal_strike
                                                    + total_optional_inputs
                                                )

            },
            changeOptionalInputs(){
                this.calculateTotal()
            },
            getTotalOptionalInputs(){

                return this.getValueFromInputUndefined(this.form.accrued.endowment)
                        + this.getValueFromInputUndefined(this.form.accrued.sustenance_support)
                        + this.getValueFromInputUndefined(this.form.accrued.withdrawal_bonus)
                        + this.getValueFromInputUndefined(this.form.accrued.compensation)
                        + this.getValueFromInputUndefined(this.form.accrued.telecommuting)
                        + this.getValueFromInputUndefined(this.form.accrued.salary_viatics)
                        + this.getValueFromInputUndefined(this.form.accrued.non_salary_viatics)
                        + this.getValueFromInputUndefined(this.form.accrued.refund)

            },
            changeDeductionOptionalInputs(){
                this.calculateTotalDeduction()
            },
            getTotalDeductionOptionalInputs(){

                return this.getValueFromInputUndefined(this.form.deduction.afc)
                        + this.getValueFromInputUndefined(this.form.deduction.refund)
                        - this.getValueFromInputUndefined(this.form.deduction.debt) // resta a deducciones
                        // + this.getValueFromInputUndefined(this.form.deduction.debt)
                        + this.getValueFromInputUndefined(this.form.deduction.education)
                        + this.getValueFromInputUndefined(this.form.deduction.voluntary_pension)
                        + this.getValueFromInputUndefined(this.form.deduction.withholding_at_source)
                        + this.getValueFromInputUndefined(this.form.deduction.cooperative)
                        + this.getValueFromInputUndefined(this.form.deduction.tax_liens)
                        + this.getValueFromInputUndefined(this.form.deduction.supplementary_plan)

            },
            sumTotalLincese(){
                this.calculateTotal()
            },
            sumTotalsExtraHoursForm(){
                // this.calculateTotalAccrued()
                this.calculateTotal()
            },
            async changeWorker() {

                // let worker = await _.find(this.workers, {id : this.form.worker_id})
                this.form.select_worker = await _.find(this.workers, {id : this.form.worker_id})

                if(!this.isAdjustNote)
                {
                    //autocompletar campos
                    await this.autocompleteDataFromWorker(this.form.select_worker)

                    //recalcular campos que utilizan el salario base del empleado para calculos, estos se ven afectados por el mismo
                    await this.recalculateData()

                    await this.calculateTotal()
                }

            },
            recalculateLicense(){
                this.$refs.componentDocumentPayrollLicenses.recalculateDataLicense()
            },
            recalculateExtraHours(){
                this.$refs.componentDocumentPayrollExtraHours.recalculateDataExtraHours()
            },
            async recalculateData(){

                // recalcular campos devengados

                await this.calculateTransportationAllowance()

                //recalcular pagos(totales) de las horas extras, estas se calculan en base al salario base
                await this.recalculateExtraHours()

                // prima de servicio
                await this.recalculateServiceBonus()

                // incapacidades
                await this.recalculateWorkDisability()

                // licencias
                await this.recalculateLicense()


                // recalcular campos deducciones

                // sindicatos
                await this.recalculateLaborUnion()

            },
            autocompleteDataFromWorker(worker){

                this.form.payroll_period_id = worker.payroll_period_id
                this.form_disabled.payroll_period_id = worker.payroll_period_id ? true : false

                this.form.period.admision_date = worker.work_start_date
                this.form_disabled.admision_date = worker.work_start_date ? true : false

                this.autocompleteDataSalary(worker.salary)

                this.autocompleteDataPayment(worker)

                this.changePayrollPeriod()

            },
            async autocompleteDataSalary(worker_salary){

                this.form.accrued.worked_days = this.quantity_days_month
                this.form.accrued.salary = parseFloat(worker_salary)
                this.form.accrued.total_base_salary = parseFloat(worker_salary) //salario completo
                // this.calculateTransportationAllowance()

                // //recalcular pagos(totales) de las horas extras, estas se calculan en base al salario base
                // await this.$refs.componentDocumentPayrollExtraHours.recalculateDataExtraHours()

            },
            changePayrollPeriod(){

                let payroll_period = _.find(this.payroll_periods, { id : this.form.payroll_period_id })
                this.cleanPaymentDates()

                // mensual
                if(payroll_period.id == 5){
                    // agregar pago con el ultimo dia del mes
                    this.clickAddPaymentDate(moment().endOf('month').format('YYYY-MM-DD'))
                }

                // quincenal
                if(payroll_period.id == 4){

                    const start_date = moment().startOf('month')
                    const end_date = moment().endOf('month').format('YYYY-MM-DD')
                    let add_quantity_days = parseInt(moment().daysInMonth() / 2) - 1
                    const fortnight = start_date.add(add_quantity_days, 'days').format('YYYY-MM-DD')

                    // quincena
                    this.clickAddPaymentDate(fortnight)

                    // fin de mes
                    this.clickAddPaymentDate(end_date)

                }

            },
            cleanPaymentDates(){
                this.form.payment_dates = []
            },
            getPaymentPerDay(){
                return this.form.accrued.total_base_salary / this.quantity_days_month
            },
            getTransportationAllowancePerDay(){
                return this.advanced_configuration.transportation_allowance / this.quantity_days_month
            },
            changeWorkedDays(){

                this.form.accrued.salary = _.round(this.getPaymentPerDay() * this.form.accrued.worked_days, 2)

                this.calculateTransportationAllowance()
                this.calculateTotal()

            },
            changeTransportationAllowance(){
                this.calculateTotalAccrued()
            },
            calculateTransportationAllowance(){

                // validar si corresponde subsidio y asignar valor

                let minimum_salary = parseFloat(this.advanced_configuration.minimum_salary)

                if(this.form.accrued.total_base_salary <= (minimum_salary * 2) && !this.form.select_worker.is_type_worker_sena){

                    this.form.accrued.transportation_allowance = _.round(this.getTransportationAllowancePerDay() * this.form.accrued.worked_days, 2)

                }else{
                    this.form.accrued.transportation_allowance = undefined
                }

            },
            autocompleteDataPayment(worker){

                if(worker.payment){

                    this.form.payment = worker.payment
                    this.form_disabled.payment = !this.isAdjustNote

                }else{

                    this.form.payment = {
                        payment_method_id: null,
                        bank_name: null,
                        account_type: null,
                        account_number: null,
                    }

                    this.form_disabled.payment = false
                }

                this.changePaymentMethod()

            },
            getErrorMessage(message){
                return {
                    success: false,
                    message: message
                }
            },
            getExistErrorEpctvBonuses(){

                let exist_error = 0
                this.form.accrued.epctv_bonuses.forEach(row => {
                    if(!row.paymentS && !row.paymentNS && !row.salary_food_payment && !row.non_salary_food_payment) exist_error++
                })

                return exist_error

            },
            validateData(){

                // validar bono EPCTVs
                if(this.getExistErrorEpctvBonuses() > 0) return this.getErrorMessage('Debe agregar al menos un valor en un campo del bono EPCTVs.')

                if(!this.form.resolution_number) return this.getErrorMessage('El número de resolución es obligatorio, debe asignarle un valor.')

                return {
                    success : true
                }

            },
            async submit() {

                const validateData = await this.validateData()
                if(!validateData.success) return this.$message.error(validateData.message)

                this.loading_submit = true

                await this.$http.post(`/${(this.isAdjustNote) ? this.resource_adjust_note : this.resource}`, this.form).then(response => {
                    // console.log(response)
                    if (response.data.success) {
                        this.resetForm()
                        this.recordId = response.data.data.id
                        this.showDialogDocumentPayrollOptions = true
                        this.checkDocumentPayrollAdjustNote()
                    }
                    else {
                        this.$message.error(response.data.message)
                    }
                }).catch(error => {

                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    }
                    else {
                        this.$message.error(error.response.data.message)
                    }


                }).then(() => {
                    this.loading_submit = false;
                });
            },
        }
    }
</script>
