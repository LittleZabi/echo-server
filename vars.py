import os
production = False
# if production:
serverURI = 'https://aliunlockers.com'
# else:
#     serverURI = 'http://localhost:380'


def __logs__(text):
    with open('logs.txt', 'w') as file:
        try:
            file.write(str(text)+'\n')
        except:
            print('Logs not saved: ', text)


def __filter__(link):
    try:
        id = link.split('id=')[1]
        k = link.split('index.php')
        return k[0]+'index.php?a=downloads&b=file&c=download&id='+id
    except:
        return link


def loading(scale=3, before=''):
    os.system('CLS')
    print(f'{before}{"." * scale}',  end='\r\n')


def __ErrFire__(module='', class_='', function='', err='', line=''):
    try:
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
            print(y, err)
            return y, err
        print(y)
        return y
    except Exception as e:
        print('[renamer.py -> __ErrFire__] creating error: ', e)
