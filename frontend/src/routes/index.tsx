import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import ClientOrders from '@/pages/client/Orders';
import DriverOrders from '@/pages/driver/Orders';
import AdminOrders from '@/pages/admin/Orders';

export default function AppRoutes() {
  return (
    <Router>
      <Routes>
        {/* Redirect root "/" to client orders */}
        <Route path="/" element={<Navigate to="/client/orders" replace />} />

        {/* Role-based pages */}
        <Route path="/client/orders" element={<ClientOrders />} />
        <Route path="/driver/orders" element={<DriverOrders />} />
        <Route path="/admin/orders" element={<AdminOrders />} />
      </Routes>
    </Router>
  );
}
