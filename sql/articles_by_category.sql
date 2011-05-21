SELECT c.catid, cat.title, count(1) numArticlesInCat
  FROM jos_content c
  JOIN jos_categories cat ON c.catid = cat.id
  GROUP BY catid
  ORDER BY numArticlesInCat DESC;

