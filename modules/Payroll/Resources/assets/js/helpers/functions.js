import moment from 'moment'

function roundNumberFormat(number, decimals = 2) 
{
    return _.round(number, decimals)
}

function getDiffInDays(start_date, end_date) 
{
    return moment(end_date, "YYYY-MM-DD").diff(moment(start_date, "YYYY-MM-DD"), 'days', true)
}

function getArrayStartEndDate(quantity)
{
    return [
        moment().format('YYYY-MM-DD'),
        moment().add(quantity, 'days').format('YYYY-MM-DD')
    ]
}

function sumTotalFromArray(array, property)
{
    return _.sumBy(array, property)
}

function getValueIfNull(input, output_value){
    return input ? input : output_value
}

export {
    roundNumberFormat, 
    getDiffInDays, 
    getArrayStartEndDate, 
    sumTotalFromArray,
    getValueIfNull
}
