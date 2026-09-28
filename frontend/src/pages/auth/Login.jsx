import { useState } from "react";
import { useNavigate } from "react-router-dom";

import { useAuth } from "../../context/AuthContext";

import "./Login.css";

function Login() {
    const navigate = useNavigate();

    const { login } = useAuth();

    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    const [showPassword, setShowPassword] =
        useState(false);

    const [error, setError] = useState("");

    const [loading, setLoading] =
        useState(false);

    const handleSubmit = async (event) => {
        event.preventDefault();

        setError("");
        setLoading(true);

        try {
            await login(
                email,
                password
            );

            navigate("/dashboard");

        } catch (error) {
            setError(
                error.response?.data?.message ||
                "Unable to sign in. Please check your credentials."
            );

        } finally {
            setLoading(false);
        }
    };

    return (
        <main className="login-page">

            <section className="login-container">

                {/* LEFT SIDE */}

                <div className="login-brand">

                    <div className="brand-logo">
                        S
                    </div>

                    <div className="brand-name">
                        SaaS
                        <span>Platform</span>
                    </div>

                    <div className="brand-content">

                        <h2>
                            One workspace.
                            <br />
                            Multiple possibilities.
                        </h2>

                        <p>
                            Manage your business from one
                            secure and centralized workspace.
                        </p>

                    </div>

                    <div className="brand-footer">
                        © 2026 SaaS Platform
                    </div>

                </div>


                {/* RIGHT SIDE */}

                <div className="login-form-section">

                    <div className="login-form-container">

                        <div className="mobile-brand">

                            <div className="brand-logo">
                                S
                            </div>

                            <div className="brand-name">
                                SaaS
                                <span>Platform</span>
                            </div>

                        </div>


                        <div className="login-heading">

                            <p className="eyebrow">
                                WELCOME BACK
                            </p>

                            <h1>
                                Sign in to your account
                            </h1>

                            <p className="subtitle">
                                Enter your credentials to
                                access your workspace.
                            </p>

                        </div>


                        <form onSubmit={handleSubmit}>

                            {/* EMAIL */}

                            <div className="input-group">

                                <label htmlFor="email">
                                    Email address
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    value={email}
                                    onChange={(event) =>
                                        setEmail(
                                            event.target.value
                                        )
                                    }
                                    placeholder="name@company.com"
                                    autoComplete="email"
                                    required
                                />

                            </div>


                            {/* PASSWORD */}

                            <div className="input-group">

                                <div className="password-label">

                                    <label htmlFor="password">
                                        Password
                                    </label>

                                    <button
                                        type="button"
                                        className="forgot-password"
                                        onClick={() => {
                                            alert(
                                                "Password recovery will be available soon."
                                            );
                                        }}
                                    >
                                        Forgot password?
                                    </button>

                                </div>


                                <div className="password-input">

                                    <input
                                        id="password"
                                        type={
                                            showPassword
                                                ? "text"
                                                : "password"
                                        }
                                        value={password}
                                        onChange={(event) =>
                                            setPassword(
                                                event.target.value
                                            )
                                        }
                                        placeholder="Enter your password"
                                        autoComplete="current-password"
                                        required
                                    />

                                    <button
                                        type="button"
                                        className="show-password"
                                        onClick={() =>
                                            setShowPassword(
                                                !showPassword
                                            )
                                        }
                                        aria-label={
                                            showPassword
                                                ? "Hide password"
                                                : "Show password"
                                        }
                                    >
                                        {showPassword
                                            ? "◉"
                                            : "◌"}
                                    </button>

                                </div>

                            </div>


                            {/* ERROR */}

                            {error && (
                                <div className="login-error">

                                    <span>
                                        !
                                    </span>

                                    <p>
                                        {error}
                                    </p>

                                </div>
                            )}


                            {/* BUTTON */}

                            <button
                                type="submit"
                                className="login-button"
                                disabled={loading}
                            >

                                {loading ? (
                                    <>
                                        <span className="spinner"></span>

                                        Signing in...
                                    </>
                                ) : (
                                    <>
                                        Sign in

                                        <span className="arrow">
                                            →
                                        </span>
                                    </>
                                )}

                            </button>

                        </form>


                        <p className="security-note">
                            Your connection is secure and protected.
                        </p>

                    </div>

                </div>

            </section>

        </main>
    );
}

export default Login;