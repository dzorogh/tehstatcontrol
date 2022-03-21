import { Classes } from './Classes';

export default {
  root: [
    'relative',
    'select-none',
    'text-sm'
  ],
  label: [
    'px-2',
    'py-1',
    'bg-zinc-100',
    'rounded',
    'flex',
    'flex-row',
    'content-center',
    'cursor-pointer',
  ],
  labelTitle: [
    'grow',
    'font-bold',
    'flex',
    'items-center'
  ],
  labelCounter: [
    'flex-none',
    'bg-teal-600',
    'text-zinc-100',
    'rounded-full',
    'w-5',
    'h-5',
    'ml-2',
    'inline-flex',
    'items-center',
    'content-center',
  ],
  labelCounterNumber: [
    'grow',
    'text-center',
    'text-xs',
  ],
  labelCounterIcon: [
    'w-3',
    'h-3',
    'rounded-full',
    'ml-2',
    'bg-emerald-800'
  ],
  labelChevronIcon: [
    'flex-none',
    'w-6',
    'h-6',
    'transition-transform',
    'ml-2'
  ],
  labelChevronIconOpen: [
    'rotate-180'
  ],
  labelLoadingIcon: [
    'flex-none',
    'w-6',
    'h-6',
    'ml-2',
    'text-zinc-800',
    'animate-spin'
  ],
  dropdown: [
    'absolute',
    'bg-zinc-200',
    'top-full',
    'left-0',
    // 'right-0',
    'rounded',
    'mt-1',
    'shadow-md',
    'overflow-auto',
    'z-10',
    'max-h-[50vh]',
    'min-w-full'
  ],
  options: [
    'divide-y',
    'divide-zinc-100'
  ],
  option: [
    'px-2',
    'py-1',
    'flex',
    'flex-row',
    'cursor-pointer',
    'items-center',
    'hover:bg-zinc-100',
  ],
  optionLabel: [
    'grow'
  ],
  optionIcon: [
    'w-6',
    'h-6',
    'ml-3',
    'p-px',
    'border-2 ',
    'border-teal-600',
    'stroke-teal-600',
    'rounded',
    'flex-none',
    'stroke-2',
  ],
} as Classes;
