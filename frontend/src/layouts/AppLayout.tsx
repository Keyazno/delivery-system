import type { ReactNode } from 'react';

interface AppLayoutProps {
  children: ReactNode;
  title?: string;
}

export default function AppLayout({ children, title }: AppLayoutProps) {
  return (
    <div className="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900">
      <header className="bg-white dark:bg-gray-800 shadow p-4">{title}</header>
      <main className="flex-1 p-6">{children}</main>
    </div>
  );
}
