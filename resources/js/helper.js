import moment   from 'moment';

export function dateFormate(values){
   return moment(String(values)).format('YYYY-MM-DD');
}
