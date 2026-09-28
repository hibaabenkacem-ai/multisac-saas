import {
    createContext,
    useContext,
    useEffect,
    useState,
} from "react";

import api from "../services/api";

const AuthContext = createContext(null);

export function AuthProvider({ children }) {

    const [user, setUser] = useState(null);

    const [loading, setLoading] = useState(true);


    useEffect(() => {

        const token =
            localStorage.getItem("token");


        // ما كاينش token
        if (!token) {

            setLoading(false);

            return;
        }


        // كاين token → نتأكد واش مازال صالح
        api.get("/me")
            .then((response) => {

                setUser(
                    response.data.user
                );

            })
            .catch((error) => {

                console.error(
                    "Authentication check failed:",
                    error
                );

                localStorage.removeItem(
                    "token"
                );

                setUser(null);

            })
            .finally(() => {

                setLoading(false);

            });

    }, []);


    const login = async (
        email,
        password
    ) => {

        const response =
            await api.post(
                "/login",
                {
                    email,
                    password,
                }
            );


        const token =
            response.data.token;

        const user =
            response.data.user;


        // نحطو token
        localStorage.setItem(
            "token",
            token
        );


        // نحطو user مباشرة
        setUser(user);


        return response.data;
    };


    const logout = async () => {

        try {

            await api.post(
                "/logout"
            );

        } catch (error) {

            console.error(
                "Logout request failed:",
                error
            );

        } finally {

            localStorage.removeItem(
                "token"
            );

            setUser(null);

        }
    };


    return (
        <AuthContext.Provider
            value={{
                user,
                loading,
                login,
                logout,
                isAuthenticated:
                    !!user,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}


export function useAuth() {
    return useContext(
        AuthContext
    );
}