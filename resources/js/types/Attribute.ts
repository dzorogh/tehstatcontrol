import { Value } from './Value';
import { DataType } from './DataType';

export interface Attribute {
  groupId: number | null;
  order: number | null;
  id: number;
  title: string;
  values: Array<Value>;
  dataType: DataType;
  description: string
}
