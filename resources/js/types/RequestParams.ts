import { RequestFilters } from './RequestFilters';
import { Sort } from './Sort';

export interface RequestParams {
  filters: RequestFilters;
  page: number;
  sort: Sort;
}
