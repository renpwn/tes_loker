"use client";

import { Input } from "@/components/ui/input";

function ClearableInput({ value, onChange, ...props }) {
  return (
    <div className="relative w-full">
      <Input
        {...props}
        value={value}
        onChange={onChange}
        className="pr-10" // ruang ekstra
      />

      {value && (
        <button
          type="button"
          onClick={() => onChange({ target: { value: "" } })}
          className="
            absolute right-2 top-1/2 -translate-y-1/2
            flex items-center justify-center
            w-6 h-6                /* buat area lebih besar */
            text-xl               /* ukuran X lebih besar */
            text-gray-500 hover:text-black
            rounded-full hover:bg-gray-200
          "
        >
          ×
        </button>
      )}
    </div>
  );
}

export default ClearableInput;
