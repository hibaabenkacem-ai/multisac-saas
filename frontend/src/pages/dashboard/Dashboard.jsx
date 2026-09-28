import { useAuth } from "../../context/AuthContext";

import DashboardLayout from "../../layouts/DashboardLayout";

import "./Dashboard.css";

function Dashboard() {
    const { user } = useAuth();

    return (
        <DashboardLayout>

            <div className="dashboard-welcome">

                <div>

                    <span className="dashboard-eyebrow">
                        OVERVIEW
                    </span>

                    <h1>
                        Good to see you,{" "}
                        {user?.name?.split(" ")[0]}.
                    </h1>

                    <p>
                        Here's what's happening in
                        your workspace.
                    </p>

                </div>


                <button className="primary-action">
                    + New invitation
                </button>

            </div>


            <div className="stats-grid">

                <div className="stat-card">

                    <div className="stat-header">

                        <span>
                            Users
                        </span>

                        <span className="stat-icon">
                            ♙
                        </span>

                    </div>

                    <strong>
                        —
                    </strong>

                    <p>
                        Members in your workspace
                    </p>

                </div>


                <div className="stat-card">

                    <div className="stat-header">

                        <span>
                            Invitations
                        </span>

                        <span className="stat-icon">
                            ✉
                        </span>

                    </div>

                    <strong>
                        —
                    </strong>

                    <p>
                        Pending invitations
                    </p>

                </div>


                <div className="stat-card">

                    <div className="stat-header">

                        <span>
                            Orders
                        </span>

                        <span className="stat-icon">
                            ▣
                        </span>

                    </div>

                    <strong>
                        —
                    </strong>

                    <p>
                        Orders in your company
                    </p>

                </div>


                <div className="stat-card">

                    <div className="stat-header">

                        <span>
                            Subscription
                        </span>

                        <span className="stat-icon">
                            ◆
                        </span>

                    </div>

                    <strong className="subscription-status">
                        {user?.company
                            ?.subscription_status ||
                            "—"}
                    </strong>

                    <p>
                        Current subscription status
                    </p>

                </div>

            </div>


            <div className="dashboard-grid">

                <section className="dashboard-card">

                    <div className="card-header">

                        <div>

                            <h3>
                                Quick actions
                            </h3>

                            <p>
                                Manage your workspace quickly.
                            </p>

                        </div>

                    </div>


                    <div className="quick-actions">

                        <button>
                            <span>✉</span>
                            Invite a user
                        </button>

                        <button>
                            <span>♙</span>
                            View users
                        </button>

                        <button>
                            <span>▣</span>
                            View orders
                        </button>

                        <button>
                            <span>◷</span>
                            Audit logs
                        </button>

                    </div>

                </section>


                <section className="dashboard-card">

                    <div className="card-header">

                        <div>

                            <h3>
                                Workspace
                            </h3>

                            <p>
                                Current account information.
                            </p>

                        </div>

                    </div>


                    <div className="workspace-info">

                        <div>

                            <span>
                                Company
                            </span>

                            <strong>
                                {user?.company?.name ||
                                    "Platform"}
                            </strong>

                        </div>


                        <div>

                            <span>
                                Role
                            </span>

                            <strong>
                                {user?.roles?.[0]?.name ||
                                    "User"}
                            </strong>

                        </div>


                        <div>

                            <span>
                                Email
                            </span>

                            <strong>
                                {user?.email}
                            </strong>

                        </div>

                    </div>

                </section>

            </div>

        </DashboardLayout>
    );
}

export default Dashboard;