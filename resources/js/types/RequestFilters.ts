import { Attribute } from './Attribute';
import { Value } from './Value';

type value = Value['value'];
type attributeId = Attribute['id'];

export interface RequestFilters {
  attributes?: { [attributeId: attributeId]: value | value[] },
  yearId?: number,
  categoryId?: number,
  groupSlug?: string;
  brandsIds?: number[]
}
