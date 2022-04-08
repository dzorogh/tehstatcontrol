import { Value } from './Value';
import { DataType } from './DataType';

export interface Product {
  id: number;
  title: string;
  category: {
    title: string;
    id: number;
  };
  brand: {
    title: string;
  };
  valuesByAttributeId: any[]
  valuesByAttributeIdAndYearId: any[]
}
