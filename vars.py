production = False
# if production:
serverURI = 'https://aliunlockers.com'
# else:
#     serverURI = 'http://localhost:380'


def __filter__(link):
    try:
        id = link.split('id=')[1]
        k = link.split('index.php')
        return k[0]+'index.php?a=downloads&b=file&c=download&id='+id
    except:
        return link


def __ErrFire__(module='', class_='', function='', err='', line=''):
    y = '['
    if module != '':
        y += module
    if class_ != '':
        y += '.'+class_
    y += ' => '
    if function != '':
        y += function
    if line != '':
        y += ': ' + line
    y += '] '
    if err != '':
        y += err
    print(y)
    return y
