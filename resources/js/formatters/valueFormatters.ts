import { DataType } from '../types/DataType';
// @ts-ignore
import plural from 'plural-ru';

function formatPercent(value) {
  if (!isNaN(value)) {
    return Math.round((parseFloat(value) * 100) * 100) / 100 + '%';
  } else {
    return value;
  }
}

function formatMonths(value) {
  if (isNaN(value)) {
    return value;
  }
  
  if (value > 12) {
    const years = Math.round(Math.floor(value / 12));
    const months = Math.round(value % 12);
    
    if (months > 0) {
      return `${years} ${plural(years, 'г', 'г', 'л')}. ${months} мес.`;
    } else {
      return `${years} ${plural(years, 'г', 'г', 'л')}.`;
    }
  } else {
    return `${value} мес.`;
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

function formatHP(value) {
  if (isNaN(value)) {
    return value;
  }
  
  return `${value} л. с.`;
}

function formatRating(value) {
  return Math.round(value * 100) / 100;
}

function formatNumber(value) {
  return Math.round(value * 100) / 100;
}

function formatCountry(value) {
  const valueTrimmed = value.trim();
  
  if (valueTrimmed) {
    try {
      return new Intl.DisplayNames(['ru'], {type: 'region'}).of(valueTrimmed);
    } catch (e) {
      console.log(e)
    }
  }
  
  return value;
}

function formatDataType(dataType: DataType, value) {
  if (dataType === 'percent') return formatPercent(value);
  if (dataType === 'months') return formatMonths(value);
  if (dataType === 'price') return formatPrice(value);
  if (dataType === 'hp') return formatHP(value);
  if (dataType === 'rating') return formatRating(value);
  if (dataType === 'number') return formatNumber(value);
  if (dataType === 'country') return formatCountry(value);
  
  return value;
}

export { formatDataType, formatHP, formatMonths, formatPercent, formatPrice, formatNumber, formatRating, formatCountry };
