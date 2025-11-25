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
  price: number;
  status: string;
}

export default function ClientOrders() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api.get('/client/orders')
      .then(res => setOrders(res.data))
      .finally(() => setLoading(false));
  }, []);

  return (
    <AppLayout title="My Orders">
      {loading ? (
        <p>Loading orders...</p>
      ) : (
        <div className="grid gap-4">
          {orders.map(order => (
            <Card key={order.id} className="p-4">
              <div className="font-bold text-lg">Order #{order.tracking_number}</div>
              <div>Pickup: {order.pickup_address}</div>
              <div>Destination: {order.destination_address}</div>
              <div>Price: ${order.price}</div>
              <div>Status: {order.status}</div>
              <Button className="mt-2">View Details</Button>
            </Card>
          ))}
        </div>
      )}
    </AppLayout>
  );
}
