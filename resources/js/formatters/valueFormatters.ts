import { DataType } from '../types/DataType';
// @ts-ignore
import plural from 'plural-ru';

function formatPercent(value) {
  if (!isNaN(value)) {
    return Math.round((parseFloat(value) * 100) * 100) / 100 + '%'
  } else {
    return value;
  }
}

function formatMonths(value) {
  if (isNaN(value)) {
    return value;
  }
  
  if (value > 12) {
    const years = Math.floor(value / 12);
    const months = value % 12;
    
    if (months > 0) {
      return `${years} ${plural(years, 'год', 'года', 'лет')} ${months} ${plural(months, 'месяц',
       'месяца', 'месяцев')}`
    } else {
      return `${years} ${plural(years, 'год', 'года', 'лет')}`
    }
  } else {
    return `${value} ${plural(value, 'месяц', 'месяца', 'месяцев')}`;
  }
}

function formatPrice(value) {
  if (isNaN(value)) {
    return value;
  }
  
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    maximumFractionDigits: 0,
    
  }).format(value);
}

function formatDataType(dataType: DataType, value) {
  if (dataType === 'percent') return formatPercent(value);
  if (dataType === 'months') return formatMonths(value);
  if (dataType === 'price') return formatPrice(value);
  
  return value;
}

export { formatDataType };
