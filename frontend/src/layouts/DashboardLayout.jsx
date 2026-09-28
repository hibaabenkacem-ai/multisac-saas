import Sidebar from "../components/layout/Sidebar";
import Topbar from "../components/layout/Topbar";

import "./DashboardLayout.css";

function DashboardLayout({ children }) {
    return (
        <div className="dashboard-layout">

            <Sidebar />

            <main className="dashboard-main">

                <Topbar />

                <div className="dashboard-page-content">
                    {children}
                </div>

            </main>

        </div>
    );
}

export default DashboardLayout;