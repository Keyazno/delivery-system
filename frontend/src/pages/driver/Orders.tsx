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
  status: string;
}

export default function DriverOrders() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get('/driver/orders')
      .then(res => setOrders(res.data))
      .finally(() => setLoading(false));
  }, []);

  const updateStatus = (id: number, status: string) => {
    api.patch(`/driver/orders/${id}/status`, { status })
      .then(res => {
        setOrders(prev => prev.map(o => o.id === id ? res.data : o));
      });
  };

  return (
    <AppLayout title="Assigned Orders">
      {loading ? (
        <p>Loading orders...</p>
      ) : (
        <div className="grid gap-4">
          {orders.map(order => (
            <Card key={order.id} className="p-4">
              <div className="font-bold">Order #{order.tracking_number}</div>
              <div>Pickup: {order.pickup_address}</div>
              <div>Destination: {order.destination_address}</div>
              <div>Status: {order.status}</div>
              <div className="mt-2 flex gap-2">
                <Button onClick={() => updateStatus(order.id, 'in_transit')}>In Transit</Button>
                <Button onClick={() => updateStatus(order.id, 'delivered')}>Delivered</Button>
                <Button onClick={() => updateStatus(order.id, 'canceled')}>Cancel</Button>
              </div>
            </Card>
          ))}
        </div>
      )}
    </AppLayout>
  );
}
