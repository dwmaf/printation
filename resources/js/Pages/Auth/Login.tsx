import { useForm, Head } from "@inertiajs/react";
import React, { useState } from "react";
import { Eye, EyeOff } from "lucide-react";

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: "",
        password: "",
    });

    const [showPassword, setShowPassword] = useState(false);

    const togglePassword = () => setShowPassword(!showPassword);

    return (
        <div className="min-h-screen bg-background">
            <Head title="Log in" />

            <div className="flex h-dvh p-4 justify-center">
                <div className="w-full md:w-1/2 flex flex-col items-center justify-center h-full gap-4">
                    <div className="flex items-center justify-center gap-1">
                        <img
                            src="/images/logo.svg"
                            alt="Logo Print App"
                            className="w-8 h-8 object-contain"
                        />

                        <span className="text-xl font-bold font-poppins">
                            PRINTATION
                        </span>
                    </div>

                    <div className="font-poppins bg-white p-6 rounded-2xl flex flex-col gap-4">
                        <div className="space-y-2">
                            <h1 className="text-3xl font-bold text-center">
                                Sign In
                            </h1>

                            <p className="text-gray-500 text-xs text-center">
                                Selamat Datang! Silakan masuk untuk melanjutkan
                            </p>
                        </div>

                        <form
                            onSubmit={(e) => {
                                e.preventDefault();
                                post("/login");
                            }}
                            className="space-y-4"
                        >
                            <div className="space-y-1.5">
                                <label className="block text-sm font-medium">
                                    Email
                                </label>

                                <input
                                    value={data.email}
                                    onChange={(e) =>
                                        setData("email", e.target.value)
                                    }
                                    type="email"
                                    name="email"
                                    placeholder="Masukkan email"
                                    className="w-full rounded-lg py-2 px-4 text-sm border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all"
                                    required
                                />

                                {errors.email && (
                                    <div className="text-red-500 text-sm">
                                        {errors.email}
                                    </div>
                                )}
                            </div>

                            <div className="space-y-1.5">
                                <label className="block text-sm font-medium">
                                    Password
                                </label>

                                <div className="relative">
                                    <input
                                        value={data.password}
                                        onChange={(e) =>
                                            setData("password", e.target.value)
                                        }
                                        type={
                                            showPassword ? "text" : "password"
                                        }
                                        name="password"
                                        id="password"
                                        placeholder="Masukkan password"
                                        className="w-full rounded-lg py-2 px-4 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all text-sm"
                                        required
                                    />

                                    <button
                                        type="button"
                                        onClick={togglePassword}
                                        className="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"
                                    >
                                        {showPassword ? (
                                            <EyeOff className="w-4 h-4" />
                                        ) : (
                                            <Eye className="w-4 h-4" />
                                        )}
                                    </button>
                                </div>

                                {errors.password && (
                                    <div className="text-red-500 text-sm">
                                        {errors.password}
                                    </div>
                                )}
                            </div>

                            <div className="flex justify-between text-sm">
                                <div className="flex gap-1.5">
                                    <input
                                        type="checkbox"
                                        className="accent-blue-600"
                                    />
                                    <p>Ingat saya</p>
                                </div>

                                <a href="#" className="text-blue-700">
                                    Lupa password
                                </a>
                            </div>

                            <button
                                type="submit"
                                className="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-xl transition-all duration-300 cursor-pointer disabled:opacity-50 text-sm"
                                disabled={processing}
                            >
                                Masuk
                            </button>

                            <div className="flex items-center gap-2">
                                <hr className="w-full border-gray-200" />
                                <p className="text-xs font-medium text-gray-200">
                                    ATAU
                                </p>
                                <hr className="w-full border-gray-200" />
                            </div>

                            <button className="border border-gray-200 py-2 w-full rounded-xl text-sm hover:bg-gray-50 transition-all duration-300 cursor-pointer flex items-center justify-center gap-2">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 16 16"
                                    className="w-4 h-4"
                                >
                                    <path d="M0 0h16v16H0z" fill="none" />
                                    <g
                                        fill="none"
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                    >
                                        <path
                                            fill="#f44336"
                                            d="M7.209 1.061c.725-.081 1.154-.081 1.933 0a6.57 6.57 0 0 1 3.65 1.82a100 100 0 0 0-1.986 1.93q-1.876-1.59-4.188-.734q-1.696.78-2.362 2.528a78 78 0 0 1-2.148-1.658a.26.26 0 0 0-.16-.027q1.683-3.245 5.26-3.86"
                                            opacity=".987"
                                        />
                                        <path
                                            fill="#ffc107"
                                            d="M1.946 4.92q.085-.013.161.027a78 78 0 0 0 2.148 1.658A7.6 7.6 0 0 0 4.04 7.99q.037.678.215 1.331L2 11.116Q.527 8.038 1.946 4.92"
                                            opacity=".997"
                                        />
                                        <path
                                            fill="#448aff"
                                            d="M12.685 13.29a26 26 0 0 0-2.202-1.74q1.15-.812 1.396-2.228H8.122V6.713q3.25-.027 6.497.055q.616 3.345-1.423 6.032a7 7 0 0 1-.51.49"
                                            opacity=".999"
                                        />
                                        <path
                                            fill="#43a047"
                                            d="M4.255 9.322q1.23 3.057 4.51 2.854a3.94 3.94 0 0 0 1.718-.626q1.148.812 2.202 1.74a6.62 6.62 0 0 1-4.027 1.684a6.4 6.4 0 0 1-1.02 0Q3.82 14.524 2 11.116z"
                                            opacity=".993"
                                        />
                                    </g>
                                </svg>
                                Masuk dengan Google
                            </button>
                        </form>
                    </div>
                </div>

                <div className="hidden w-125 md:flex flex-col bg-blue-600 items-center justify-center p-10 rounded-xl font-poppins text-white gap-12 text-center">
                    <div className="space-y-4">
                        <h1 className="text-4xl font-semibold ">
                            Selamat Datang!
                        </h1>

                        <h2 className="font-semibold text-3xl">
                            Silakan masuk dengan akun <u>Printation</u> anda
                        </h2>

                        <p className="text-xs text-gray-200">
                            Kelola pesanan dengan mudah, pantau proses
                            pencetakan secara real-time, dan akses seluruh fitur
                            Printation dalam satu tempat.
                        </p>
                    </div>

                    <img
                        src="/images/login-image.png"
                        alt="Login Illustration"
                        className="relative z-10 w-full max-w-sm h-auto object-contain drop-shadow-2xl"
                    />
                </div>
            </div>
        </div>
    );
}
