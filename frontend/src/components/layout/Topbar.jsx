import { useAuth } from "../../context/AuthContext";

import "./Topbar.css";

function Topbar() {
    const {
        user,
        logout,
    } = useAuth();

    const handleLogout = async () => {
        await logout();
    };

    return (
        <header className="topbar">

            <div>

                <h2>
                    Dashboard
                </h2>

                <p>
                    Welcome back, {user?.name}
                </p>

            </div>


            <div className="topbar-right">

                <div className="company-badge">

                    <span className="company-dot"></span>

                    {user?.company?.name ||
                        "Platform"}

                </div>


                <button
                    className="logout-button"
                    onClick={handleLogout}
                >
                    Logout
                </button>

            </div>

        </header>
    );
}

export default Topbar;