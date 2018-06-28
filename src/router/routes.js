
export default [
  {
    path: '/',
    component: () => import('layouts/default'),
    children: [
      { path: '', component: () => import('pages/index') },
      { path: 'stats', component: () => import('pages/stats') }
    ]
  },

  { // 404 Not Found. Always leave this as last one
    path: '*',
    component: () => import('layouts/default'),
    children: [
      {
        path: '',
        component: () => import('pages/index'),
        meta: { error404: true }
      }
    ]
  }
]
