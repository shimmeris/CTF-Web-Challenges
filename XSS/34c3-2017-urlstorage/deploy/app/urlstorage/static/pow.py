import sys, random, string, struct
from hashlib import sha256

PROOF_OF_WORK_HARDNESS = 2**24

def proof_of_work_okay(task, solution):
    h = sha256(task.encode('ASCII') + struct.pack('<Q', solution)).hexdigest()
    return int(h, 16) < 2**256 / PROOF_OF_WORK_HARDNESS

def random_string(length = 10):
    characters = string.ascii_letters + string.digits
    return ''.join(random.choice(characters) for _ in range(length))

def solve_proof_of_work(task):
    ''' You can use this to solve the proof of work. '''
    print('Creating proof of work for {}'.format(task))
    i = 0
    while True:
        if i % 1000000 == 0: print('Progress: %d' % i)
        if proof_of_work_okay(task, i):
            print('Solution: {}'.format(i))
            return i
        i += 1
    raise ValueError('could not create proof of work')

if __name__ == '__main__':
    if sys.version[0] == '2':
        input = raw_input

    if sys.argv[1] == 'ask':
        challenge = random_string()
        print('Proof of work challenge: {}'.format(challenge))
        sol = int(input('Your response? '))
        if not proof_of_work_okay(challenge, sol):
            print('Wrong :(')
            exit(1)
    else:
        challenge = input('Challenge? ')
        print(solve_proof_of_work(challenge))
