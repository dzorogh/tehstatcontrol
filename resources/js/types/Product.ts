import { Value } from './Value';
import { DataType } from './DataType';

export interface Product {
  id: number;
  title: string;
  category: {
    title: string;
  };
  brand: {
    title: string;
  };
  valuesByAttributeId: any[]
}
