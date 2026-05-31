import {
    forwardRef,
    useId,
    type InputHTMLAttributes,
    type ReactNode,
} from "react";

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    label: string;
    error?: string;
    suffix?: ReactNode;
}

export const Input = forwardRef<HTMLInputElement, InputProps>(
    (
        { label, error, className = "", type = "text", suffix, ...props },
        ref,
    ) => {
        const generatedId = useId();
        const inputId = props.id || generatedId;

        return (
            <div className="w-full space-y-1.5">
                <label
                    htmlFor={inputId}
                    className="block text-sm font-medium text-gray-700"
                >
                    {label}
                </label>

                <div className="relative w-full">
                    <input
                        ref={ref}
                        id={inputId}
                        type={type}
                        className={`w-full rounded-lg border border-gray-200 px-4 py-2 text-sm transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none disabled:opacity-60 ${className}`}
                        {...props}
                    />

                    {suffix && (
                        <div className="absolute top-1/2 right-4 flex -translate-y-1/2 items-center justify-center">
                            {suffix}
                        </div>
                    )}
                </div>

                {error && (
                    <span className="mt-0.5 block text-xs font-medium text-red-500">
                        {error}
                    </span>
                )}
            </div>
        );
    },
);

Input.displayName = "Input";
