const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"debugbar.openhandler":{"uri":"_debugbar\/open","methods":["GET","HEAD"]},"debugbar.clockwork":{"uri":"_debugbar\/clockwork\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"debugbar.assets.css":{"uri":"_debugbar\/assets\/stylesheets","methods":["GET","HEAD"]},"debugbar.assets.js":{"uri":"_debugbar\/assets\/javascript","methods":["GET","HEAD"]},"debugbar.cache.delete":{"uri":"_debugbar\/cache\/{key}\/{tags?}","methods":["DELETE"],"parameters":["key","tags"]},"nova.api.":{"uri":"nova-api\/{resource}\/{resourceId}\/attach-morphed\/{relatedResource}","methods":["POST"],"parameters":["resource","resourceId","relatedResource"]},"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"vapor-ui":{"uri":"vapor-ui\/{view?}","methods":["GET","HEAD"],"wheres":{"view":"(.*)"},"parameters":["view"]},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"nova.pages.login":{"uri":"nova\/login","methods":["GET","HEAD"]},"nova.login":{"uri":"nova\/login","methods":["POST"]},"nova.logout":{"uri":"nova\/logout","methods":["POST"]},"nova.pages.password.email":{"uri":"nova\/password\/reset","methods":["GET","HEAD"]},"nova.password.email":{"uri":"nova\/password\/email","methods":["POST"]},"nova.pages.password.reset":{"uri":"nova\/password\/reset\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"nova.password.reset":{"uri":"nova\/password\/reset","methods":["POST"]},"nova.pages.403":{"uri":"nova\/403","methods":["GET","HEAD"]},"nova.pages.404":{"uri":"nova\/404","methods":["GET","HEAD"]},"nova.pages.home":{"uri":"nova","methods":["GET","HEAD"]},"nova.pages.dashboard":{"uri":"nova\/dashboard","methods":["GET","HEAD","POST","PUT","PATCH","DELETE","OPTIONS"]},"nova.pages.dashboard.custom":{"uri":"nova\/dashboards\/{name}","methods":["GET","HEAD"],"parameters":["name"]},"nova.pages.index":{"uri":"nova\/resources\/{resource}","methods":["GET","HEAD"],"parameters":["resource"]},"nova.pages.create":{"uri":"nova\/resources\/{resource}\/new","methods":["GET","HEAD"],"parameters":["resource"]},"nova.pages.detail":{"uri":"nova\/resources\/{resource}\/{resourceId}","methods":["GET","HEAD"],"parameters":["resource","resourceId"]},"nova.pages.edit":{"uri":"nova\/resources\/{resource}\/{resourceId}\/edit","methods":["GET","HEAD"],"parameters":["resource","resourceId"]},"nova.pages.replicate":{"uri":"nova\/resources\/{resource}\/{resourceId}\/replicate","methods":["GET","HEAD"],"parameters":["resource","resourceId"]},"nova.pages.lens":{"uri":"nova\/resources\/{resource}\/lens\/{lens}","methods":["GET","HEAD"],"parameters":["resource","lens"]},"nova.pages.attach":{"uri":"nova\/resources\/{resource}\/{resourceId}\/attach\/{relatedResource}","methods":["GET","HEAD"],"parameters":["resource","resourceId","relatedResource"]},"nova.pages.edit-attached":{"uri":"nova\/resources\/{resource}\/{resourceId}\/edit-attached\/{relatedResource}\/{relatedResourceId}","methods":["GET","HEAD"],"parameters":["resource","resourceId","relatedResource","relatedResourceId"]},"api.games.info":{"uri":"api\/games\/{game}","methods":["GET","HEAD"],"parameters":["game"],"bindings":{"game":"uuid"}},"api.game.stats":{"uri":"api\/games\/{game}\/stats","methods":["GET","HEAD"],"parameters":["game"],"bindings":{"game":"uuid"}},"api.game.create":{"uri":"api\/games","methods":["POST"]},"api.user.info":{"uri":"api\/users\/{user}","methods":["GET","HEAD"],"parameters":["user"],"bindings":{"user":"username"}},"home":{"uri":"\/","methods":["GET","HEAD"]},"game.create":{"uri":"game","methods":["POST"]},"game.play":{"uri":"game\/{game}","methods":["GET","HEAD"],"parameters":["game"],"bindings":{"game":"uuid"}},"game.solve":{"uri":"game\/{game}\/solve","methods":["POST"],"parameters":["game"],"bindings":{"game":"uuid"}},"register":{"uri":"register","methods":["GET","HEAD"]},"login":{"uri":"login","methods":["GET","HEAD"]},"verify-login":{"uri":"verify-login\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"logout":{"uri":"logout","methods":["POST"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };