export interface Sort {
  type: 'attribute' | 'title' | 'brand' | 'category';
  attributeId?: number;
  direction: 'asc' | 'desc'
}
