import { NavLink } from "react-router-dom";

import { useAuth } from "../../context/AuthContext";

import "./Sidebar.css";

function Sidebar() {
    const { user } = useAuth();

    return (
        <aside className="sidebar">

            <div className="sidebar-brand">

                <div className="sidebar-logo">
                    S
                </div>

                <div>

                    <div className="sidebar-brand-name">
                        SaaS
                        <span>Platform</span>
                    </div>

                    <div className="sidebar-brand-subtitle">
                        Workspace
                    </div>

                </div>

            </div>


            <nav>

                <div className="sidebar-section">

                    <span className="sidebar-section-title">
                        MAIN
                    </span>

                    <NavLink
                        to="/dashboard"
                        className={({ isActive }) =>
                            `sidebar-link ${
                                isActive
                                    ? "active"
                                    : ""
                            }`
                        }
                    >
                        <span className="sidebar-icon">
                            ⌂
                        </span>

                        Dashboard
                    </NavLink>

                </div>


                <div className="sidebar-section">

                    <span className="sidebar-section-title">
                        ACCOUNT
                    </span>

                    <div className="sidebar-user">

                        <div className="sidebar-avatar">
                            {user?.name
                                ?.charAt(0)
                                ?.toUpperCase()}
                        </div>

                        <div className="sidebar-user-info">

                            <strong>
                                {user?.name}
                            </strong>

                            <span>
                                {user?.roles?.[0]?.name ||
                                    "User"}
                            </span>

                        </div>

                    </div>

                </div>

            </nav>

        </aside>
    );
}

export default Sidebar;