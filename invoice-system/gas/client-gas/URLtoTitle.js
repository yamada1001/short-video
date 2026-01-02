function URLtoTitle(url) {
  var response = UrlFetchApp.fetch(url);

  var myRegexp = /<title>([\s\S]*?)<\/title>/i;
  var match = myRegexp.exec(response.getContentText());
  var title = match[1];

  title = title.replace(/(^\s+)|(\s+$)/g, "");
  return(title);
}