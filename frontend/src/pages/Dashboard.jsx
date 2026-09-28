import { useAuth } from "../context/AuthContext";

function Dashboard() {
    const { user } = useAuth();

    return (
        <div>
            <h1>Dashboard</h1>

            <p>
                Bienvenue {user?.name}
            </p>

            <p>
                Email: {user?.email}
            </p>

            <p>
                Company: {user?.company?.name || "Platform"}
            </p>
        </div>
    );
}

export default Dashboard;