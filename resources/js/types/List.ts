import { Value } from './Value';
import { DataType } from './DataType';
import { AvailableFilters } from './AvailableFilters';
import { Meta } from './Meta';
import { Attribute } from './Attribute';
import { Sort } from './Sort';
import { RequestParams } from './RequestParams';
import { RequestFilters } from './RequestFilters';

export interface List {
  data: Array<any>;
  dynamicColumns: Array<any>;
  availableFilters: AvailableFilters;
  requestFilters: RequestFilters;
  meta: Meta;
  chart: {
    attribute: Attribute
    brands: string[]
    values: number[]
  }[];
  requestSort: Sort;
}
