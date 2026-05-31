import { useForm, Head, Link } from "@inertiajs/react";
import { useState } from "react";
import { Eye, EyeOff } from "lucide-react";
import { Icon } from "@iconify/react";
import { Input } from "@/Components/ui/Input";

interface LoginFormState extends Record<string, string | boolean> {
    email: string;
    password: string;
    remember: boolean;
}

export default function Login() {
    const { data, setData, post, processing, errors } = useForm<LoginFormState>(
        {
            email: "",
            password: "",
            remember: false,
        },
    );

    const [showPassword, setShowPassword] = useState<boolean>(false);

    const togglePassword = (): void => setShowPassword((prev) => !prev);

    return (
        <div className="bg-background min-h-screen">
            <Head title="Log in" />

            <div className="flex h-dvh justify-center gap-4 p-4">
                {/* Sisi Kiri: Panel Autentikasi */}
                <div className="flex h-full w-full flex-col items-center justify-center gap-4">
                    {/* Identitas Brand */}
                    <div className="flex items-center justify-center gap-1">
                        <img
                            src="/images/logo.svg"
                            alt="Logo Print App"
                            className="h-8 w-8 object-contain xl:h-12 xl:w-12"
                        />

                        <span className="font-head text-4xl font-bold text-blue-700 xl:text-5xl">
                            Printation
                        </span>
                    </div>

                    {/* Kotak Form Utama */}
                    <div className="font-body flex flex-col gap-4 rounded-2xl bg-white p-6">
                        <div className="space-y-2">
                            <h1 className="text-center text-3xl font-bold">
                                Sign In
                            </h1>

                            <p className="text-center text-xs text-gray-500 lg:text-sm">
                                Selamat Datang! Silakan masuk untuk melanjutkan
                            </p>
                        </div>

                        <form
                            onSubmit={(e) => {
                                e.preventDefault();
                                post("/login");
                            }}
                            noValidate
                            className="space-y-4"
                        >
                            <div className="space-y-1.5">
                                <Input
                                    label="Email"
                                    type="email"
                                    name="email"
                                    placeholder="Masukkan email"
                                    value={data.email}
                                    onChange={(e) =>
                                        setData("email", e.target.value)
                                    }
                                    error={errors.email}
                                    required
                                    autoFocus
                                />
                            </div>

                            <div className="relative space-y-1.5">
                                <Input
                                    label="Password"
                                    type={showPassword ? "text" : "password"}
                                    name="password"
                                    placeholder="Masukkan password"
                                    value={data.password}
                                    onChange={(e) =>
                                        setData("password", e.target.value)
                                    }
                                    error={errors.password}
                                    className="pr-12"
                                    required
                                    suffix={
                                        <button
                                            type="button"
                                            onClick={togglePassword}
                                            className="absolute top-1/2 right-1 -translate-y-1/2 transform cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none"
                                            aria-label={
                                                showPassword
                                                    ? "Sembunyikan sandi"
                                                    : "Tampilkan sandi"
                                            }
                                        >
                                            {showPassword ? (
                                                <EyeOff className="h-4 w-4" />
                                            ) : (
                                                <Eye className="h-4 w-4" />
                                            )}
                                        </button>
                                    }
                                />
                            </div>

                            {/* Opsi Tambahan Form */}
                            <div className="flex justify-between text-sm">
                                <div className="flex gap-1.5">
                                    <input
                                        id="remember"
                                        type="checkbox"
                                        checked={data.remember}
                                        onChange={(e) =>
                                            setData((prevData) => ({
                                                ...prevData,
                                                remember: e.target.checked,
                                            }))
                                        }
                                        className="cursor-pointer accent-blue-600"
                                    />
                                    <label
                                        htmlFor="remember"
                                        className="cursor-pointer text-gray-600 transition-colors hover:text-black"
                                    >
                                        Ingat saya
                                    </label>
                                </div>

                                <Link
                                    href="#" // ! ini nanti route page lupa password
                                    className="text-blue-700"
                                >
                                    Lupa password?
                                </Link>
                            </div>

                            {/* Tombol Aksi Utama */}
                            <button
                                type="submit"
                                className="w-full cursor-pointer rounded-xl bg-blue-600 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                                disabled={processing}
                            >
                                {processing ? "Memverifikasi..." : "Masuk"}
                            </button>

                            {/* Pembatas Alternatif */}
                            <div className="flex items-center gap-2">
                                <hr className="w-full border-gray-500" />
                                <p className="text-xs font-medium text-gray-500">
                                    ATAU
                                </p>
                                <hr className="w-full border-gray-500" />
                            </div>

                            {/* Tombol OAuth Google */}
                            <Link
                                href="/auth/google/redirect" // ! disini route ke google
                                className="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-gray-200 py-2.5 text-sm transition-all duration-300 hover:bg-gray-50"
                            >
                                <Icon
                                    icon="material-icon-theme:google"
                                    className="h-4 w-4"
                                />
                                <span>Masuk dengan Google</span>
                            </Link>
                        </form>
                    </div>
                </div>

                {/* Sisi Kanan - Welcome Panel */}
                <div className="font-body hidden flex-col items-center justify-center gap-12 rounded-xl bg-blue-600 p-10 text-center text-white md:flex">
                    <div className="space-y-4">
                        <h1 className="text-4xl font-semibold">
                            Selamat Datang!
                        </h1>

                        <h2 className="text-3xl font-semibold">
                            Silakan masuk dengan akun <u>Printation</u> anda
                        </h2>

                        <p className="text-xs text-gray-100">
                            Kelola pesanan dengan mudah, pantau proses
                            pencetakan secara real-time, dan akses seluruh fitur
                            Printation dalam satu tempat.
                        </p>
                    </div>

                    <div className="relative aspect-4/3 w-full max-w-sm overflow-hidden">
                        <img
                            src="/images/login-image.webp"
                            alt="Login Illustration"
                            width={384}
                            height={288}
                            loading="eager"
                            decoding="async"
                            className="relative z-10 h-auto w-full max-w-sm object-contain drop-shadow-2xl"
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
