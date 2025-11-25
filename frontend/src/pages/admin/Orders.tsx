import { useEffect, useState } from 'react';
import AppLayout from '@/layouts/AppLayout';
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import api from '@/api';

interface Order {
  id: number;
  tracking_number: string;
  pickup_address: string;
  destination_address: string;
  client_id: number;
  driver_id?: number;
  status: string;
}

export default function AdminOrders() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get('/admin/orders')
      .then(res => setOrders(res.data))
      .finally(() => setLoading(false));
  }, []);

  const assignDriver = (orderId: number) => {
    const driverId = prompt("Enter driver ID:");
    if (!driverId) return;
    api.post(`/admin/orders/${orderId}/assign-driver`, { driver_id: driverId })
      .then(res => {
        setOrders(prev => prev.map(o => o.id === orderId ? res.data : o));
      });
  };

  const cancelOrder = (orderId: number) => {
    api.post(`/admin/orders/${orderId}/cancel`)
      .then(res => {
        setOrders(prev => prev.map(o => o.id === orderId ? res.data : o));
      });
  };

  return (
    <AppLayout title="All Orders">
      {loading ? (
        <p>Loading orders...</p>
      ) : (
        <div className="grid gap-4">
          {orders.map(order => (
            <Card key={order.id} className="p-4">
              <div className="font-bold text-lg">Order #{order.tracking_number}</div>
              <div>Pickup: {order.pickup_address}</div>
              <div>Destination: {order.destination_address}</div>
              <div>Status: {order.status}</div>
              <div className="mt-2 flex gap-2">
                <Button onClick={() => assignDriver(order.id)}>Assign Driver</Button>
                <Button onClick={() => cancelOrder(order.id)}>Cancel</Button>
              </div>
            </Card>
          ))}
        </div>
      )}
    </AppLayout>
  );
}
