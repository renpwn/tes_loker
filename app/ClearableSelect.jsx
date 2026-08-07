"use client";

import { ChevronDown } from "lucide-react";

export default function ClearableSelect({ value, onChange, children, ...props }) {
  return (
    <div className="relative w-full">
        <select
            {...props}
            value={value}
            onChange={onChange}
            className="w-full border rounded-md px-3 py-2 pr-10 bg-white appearance-none"
        >
            {children}
        </select>

        {value && (
            <button
            type="button"
            onClick={() => onChange({ target: { value: "" } })}
            className="
                absolute right-6 top-1/2 -translate-y-1/2
                flex items-center justify-center
                w-6 h-6 text-xl
                text-black-800
                rounded-full hover:bg-gray-200
            "
            >
            ×
            </button>
        )}

        {/* Custom arrow */}
        <div className="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-black-800">
            <ChevronDown size={16} />
        </div>
    </div>
  );
}
