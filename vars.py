# serverURI = 'http://localhost:380'
serverURI = 'https://aliunlockers.com'


def __filter__(link):
    try:
        id = link.split('id=')[1]
        k = link.split('index.php')
        return k[0]+'index.php?a=downloads&b=file&c=download&id='+id
    except:
        return link
